<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
// use Mockery;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_create_handles_empty_categories()
    {
        $categoryMock = Mockery::mock(Category::class);
        $categoryMock->shouldReceive('all')->once()->andReturn(collect([]));

        $post = new Post();
        $controller = new PostController($post, $categoryMock);

        $response = $controller->create();

        $this->assertEquals('users.posts.create', $response->getName());
        $this->assertEmpty($response->getData()['all_categories']);
    }

    public function test_store_post_with_valid_data()
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('post.jpg');

        $response = $this->post(route('post.store'), [
            'category' => [$category->id],
            'description' => 'This is a test post description.',
            'image' => $image,
        ]);

        $response->assertRedirect(route('index'));
        $this->assertDatabaseHas('posts', [
            'description' => 'This is a test post description.',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_store_post_with_invalid_category()
    {
        $response = $this->post(route('post.store'), [
            'category' => 'not-an-array',
            'description' => 'This is a test post description.',
            'image' => UploadedFile::fake()->image('post.jpg'),
        ]);

        $response->assertSessionHasErrors('category');

        $response = $this->post(route('post.store'), [
            'category' => [1, 2, 3, 4],
            'description' => 'This is a test post description.',
            'image' => UploadedFile::fake()->image('post.jpg'),
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_update_post_with_valid_data()
    {
        Storage::fake('public');
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $newImage = UploadedFile::fake()->image('new_post.jpg');

        $response = $this->put(route('post.update', $post->id), [
            'category' => [$post->categoryPost->first()->category_id],
            'description' => 'Updated description.',
            'image' => $newImage,
        ]);

        $response->assertRedirect(route('post.show', $post->id));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'description' => 'Updated description.',
        ]);
    }
}
