<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Post;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'hero' => section_data('hero'),
            'highlights' => section_data('highlights')['items'] ?? [],
            'about' => section_data('about'),
            'bookingStrip' => section_data('booking_strip'),
            'latestPosts' => Post::query()->published()->with(['category', 'author'])->latest('published_at')->take(3)->get(),
            'services' => Service::query()->active()->take(6)->get(),
            'panels' => Announcement::query()->live()->whereNotNull('image')->take(3)->get(),
            'announcements' => Announcement::query()->live()->whereNull('image')->get(),
            'testimonials' => Testimonial::query()->active()->get(),
        ]);
    }
}
