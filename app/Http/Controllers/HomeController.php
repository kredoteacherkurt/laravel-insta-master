<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $post;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        
        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    # Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $home_posts = [];
        $all_posts = $this->post->latest()->get();

        foreach ($all_posts as $post) {
            if (Auth::user()->id == $post->user_id || $post->user->isFollowed()) {
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    # Get the users that the Auth user is not following
    private function getSuggestedUsers()
    {
        $suggested_users = [];
        $all_users = $this->user->all()->except(Auth::user()->id);

        foreach ($all_users as $user) {
            if (!$user->isFollowed()) {
            // if ($user->id !== Auth::user()->id && !$user->isFollowed())
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 5);
        /*
        array_slice(x,y,z)
        x -- array
        y -- offset/starting index
        z -- length/how many
        */
    }

    # Search for a user name from the database
    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        
        return view('users.search')
                ->with('users', $users)
                ->with('search', $request->search);
    }
}
