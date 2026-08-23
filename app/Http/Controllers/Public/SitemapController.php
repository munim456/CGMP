<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'posts' => Post::query()->published()->latest('published_at')->get(['slug', 'updated_at']),
                'services' => Service::query()->active()->get(['slug', 'updated_at']),
                'pages' => \App\Models\Page::query()->active()->get(['slug', 'updated_at']),
            ])
            ->header('Content-Type', 'application/xml');
    }
}
