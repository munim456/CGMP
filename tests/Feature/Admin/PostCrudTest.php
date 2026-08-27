<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_a_draft_post_without_an_image(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'A draft post',
            'body' => 'Some body content.',
            'status' => 'draft',
        ]);

        $post = Post::firstWhere('title', 'A draft post');
        $response->assertRedirect(route('admin.posts.edit', $post));
        $this->assertNotNull($post);
        $this->assertSame('draft', $post->status);
        $this->assertSame('a-draft-post', $post->slug);
        $this->assertNull($post->published_at);
    }

    public function test_publishing_without_featured_image_fails_validation(): void
    {
        $category = Category::create(['name' => 'News', 'slug' => 'news']);

        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Missing image post',
            'body' => 'Body content.',
            'status' => 'published',
            'category_id' => $category->id,
            'featured_image_alt' => 'Alt text',
        ]);

        $response->assertSessionHasErrors('featured_image');
        $this->assertDatabaseMissing('posts', ['title' => 'Missing image post']);
    }

    public function test_publishing_without_alt_text_fails_validation(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'News', 'slug' => 'news']);

        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Missing alt post',
            'body' => 'Body content.',
            'status' => 'published',
            'category_id' => $category->id,
            'featured_image' => UploadedFile::fake()->image('post.jpg', 800, 600),
        ]);

        $response->assertSessionHasErrors('featured_image_alt');
    }

    public function test_publishing_with_all_required_fields_succeeds(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'News', 'slug' => 'news']);

        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'A complete post',
            'body' => 'Body content.',
            'status' => 'published',
            'category_id' => $category->id,
            'featured_image_alt' => 'Alt text',
            'featured_image' => UploadedFile::fake()->image('post.jpg', 800, 600),
        ]);

        $post = Post::firstWhere('title', 'A complete post');
        $response->assertRedirect(route('admin.posts.edit', $post));
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertNotNull($post->featured_image);
        Storage::disk('public')->assertExists($post->featured_image);
    }

    public function test_scheduling_without_a_date_fails_validation(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Scheduled post',
            'body' => 'Body content.',
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors('scheduled_for');
    }

    public function test_scheduling_with_a_past_date_fails_validation(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Scheduled post',
            'body' => 'Body content.',
            'status' => 'scheduled',
            'scheduled_for' => now()->subDay()->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('scheduled_for');
    }

    public function test_scheduling_with_a_future_date_succeeds(): void
    {
        $future = now()->addDays(3);

        $response = $this->actingAs($this->admin())->post(route('admin.posts.store'), [
            'title' => 'Scheduled post',
            'body' => 'Body content.',
            'status' => 'scheduled',
            'scheduled_for' => $future->toDateTimeString(),
        ]);

        $post = Post::firstWhere('title', 'Scheduled post');
        $response->assertRedirect(route('admin.posts.edit', $post));
        $this->assertSame('scheduled', $post->status);
        $this->assertNull($post->published_at);
        $this->assertNotNull($post->scheduled_for);
    }

    public function test_duplicate_titles_get_unique_slugs(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Same Title',
            'body' => 'First.',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'Same Title',
            'body' => 'Second.',
            'status' => 'draft',
        ]);

        $slugs = Post::where('title', 'Same Title')->pluck('slug')->sort()->values();
        $this->assertSame(['same-title', 'same-title-2'], $slugs->all());
    }

    public function test_toggle_publish_flips_status(): void
    {
        $post = Post::create([
            'title' => 'Toggle me',
            'slug' => 'toggle-me',
            'body' => 'Body',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin())->patch(route('admin.posts.toggle-publish', $post));

        $response->assertRedirect();
        $this->assertSame('published', $post->fresh()->status);

        $this->actingAs($this->admin())->patch(route('admin.posts.toggle-publish', $post));
        $this->assertSame('draft', $post->fresh()->status);
    }

    public function test_destroy_removes_post_and_detaches_tags(): void
    {
        $post = Post::create([
            'title' => 'Delete me',
            'slug' => 'delete-me',
            'body' => 'Body',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin())->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('post_tag', ['post_id' => $post->id]);
    }

    public function test_signed_preview_url_renders_unpublished_post(): void
    {
        $post = Post::create([
            'title' => 'Preview me',
            'slug' => 'preview-me',
            'body' => 'Body',
            'status' => 'draft',
        ]);

        $url = URL::signedRoute('admin.posts.preview', ['post' => $post]);

        $this->actingAs($this->admin())->get($url)->assertOk();
    }

    public function test_preview_without_valid_signature_is_forbidden(): void
    {
        $post = Post::create([
            'title' => 'No signature',
            'slug' => 'no-signature',
            'body' => 'Body',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.posts.preview', ['post' => $post]));

        $response->assertForbidden();
    }

    public function test_non_admin_cannot_manage_posts(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)->get(route('admin.posts.index'))->assertForbidden();
    }
}
