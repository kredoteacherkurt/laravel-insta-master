<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category) {
        $this->post = $post;
        $this->category = $category;
    }

    # To open the Create Post page
    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    # To save a post
    public function store(Request $request)
    {
        # 1. Validate all form data
        $request->validate([
            'category'      => 'required|array|between:1,3',
            'description'   => 'required|max:1000',
            'image'         => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. Save the post
        $this->post->description    = $request->description;
        $this->post->image          = 'data:image/' . $request->image->extension() . 
                                        ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->user_id        = Auth::user()->id;
        $this->post->save();

        # 3. Save the categories to the category_post table
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);

        # 4. Go back to homepage. Update this later.
        return redirect()->route('index');
    }

    # To open Show Post page
    public function show($id)
    {
        $post = $this->post->findOrFail($id);
        return view('users.posts.show')->with('post', $post);
    }

    # To open the Edit Post page
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);
        $all_categories = $this->category->all();

        # Get all category IDs of this post. Save in an array.
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {
        # 1. Validate all form data
        $request->validate([
            'category'      => 'required|array|between:1,3',
            'description'   => 'required|max:1000',
            'image'         => 'mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. Update the post
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;

        // If there is a new image...
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . 
                            ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        # 3. Delete all records from category_post related to this post
        $post->categoryPost()->delete();

        # 4. Save the new categories to category_post
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        # 5. Redirect to Show Post page (to confirm the update)
        return redirect()->route('post.show', $id);
    }

    public function destroy($id)
    {
        $this->post->findOrFail($id)->forceDelete();
        return redirect()->route('index');
    }
}
