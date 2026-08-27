<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Publish scheduled posts whose scheduled_for time has passed';

    public function handle(): int
    {
        $due = Post::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_for')
            ->where('scheduled_for', '<=', now())
            ->get();

        foreach ($due as $post) {
            $post->update([
                'status' => 'published',
                'published_at' => $post->scheduled_for,
                'scheduled_for' => null,
            ]);
            $this->info("Published: {$post->title}");
        }

        if ($due->isEmpty()) {
            $this->info('No scheduled posts due.');
        }

        return self::SUCCESS;
    }
}
