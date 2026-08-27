<?php

namespace Tests\Feature\Console;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishScheduledPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_publishes_posts_whose_scheduled_time_has_passed(): void
    {
        $due = Post::create([
            'title' => 'Due post',
            'slug' => 'due-post',
            'body' => 'Body',
            'status' => 'scheduled',
            'scheduled_for' => now()->subMinute(),
        ]);

        $this->artisan('posts:publish-scheduled')->assertExitCode(0);

        $due->refresh();
        $this->assertSame('published', $due->status);
        $this->assertNull($due->scheduled_for);
        $this->assertNotNull($due->published_at);
    }

    public function test_it_leaves_future_scheduled_posts_untouched(): void
    {
        $future = Post::create([
            'title' => 'Future post',
            'slug' => 'future-post',
            'body' => 'Body',
            'status' => 'scheduled',
            'scheduled_for' => now()->addDay(),
        ]);

        $this->artisan('posts:publish-scheduled');

        $future->refresh();
        $this->assertSame('scheduled', $future->status);
        $this->assertNotNull($future->scheduled_for);
    }

    public function test_it_does_not_touch_drafts_or_already_published_posts(): void
    {
        $draft = Post::create([
            'title' => 'Draft post',
            'slug' => 'draft-post',
            'body' => 'Body',
            'status' => 'draft',
        ]);

        $published = Post::create([
            'title' => 'Published post',
            'slug' => 'published-post',
            'body' => 'Body',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->artisan('posts:publish-scheduled');

        $this->assertSame('draft', $draft->fresh()->status);
        $this->assertSame('published', $published->fresh()->status);
    }
}
