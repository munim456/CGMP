<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Doctor;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'postCount' => Post::count(),
            'publishedCount' => Post::query()->published()->count(),
            'serviceCount' => Service::count(),
            'doctorCount' => Doctor::count(),
            'unreadMessages' => ContactMessage::query()->where('is_read', false)->latest()->take(5)->get(),
            'unreadCount' => ContactMessage::query()->where('is_read', false)->count(),
            'recentPosts' => Post::query()->with('category')->latest('updated_at')->take(5)->get(),
            'activeAnnouncements' => Announcement::query()->where('is_active', true)->count(),
        ]);
    }
}
