<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DoctorController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', AboutController::class)->name('about');
Route::get('/doctors', DoctorController::class)->name('doctors');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/admin/posts/{post}/preview', [Admin\PostController::class, 'preview'])
    ->middleware('signed')
    ->name('admin.posts.preview');

Route::get('/book-appointment', BookingController::class)->name('booking');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact-form')
    ->name('contact.store');

Route::get('/privacy-policy', PageController::class)->defaults('static_slug', 'privacy-policy')->name('pages.privacy');
Route::get('/terms-of-use', PageController::class)->defaults('static_slug', 'terms-of-use')->name('pages.terms');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn () => response()->view('robots')->header('Content-Type', 'text/plain'));

// Target for Laravel's default auth redirect so guests get sent to the admin login
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::patch('posts/{post}/toggle-publish', [Admin\PostController::class, 'togglePublish'])->name('posts.toggle-publish');
        Route::post('posts/upload-image', [Admin\PostController::class, 'uploadImage'])->name('posts.upload-image');

        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('tags', Admin\TagController::class)->except('show');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('doctors', Admin\DoctorController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('announcements', Admin\AnnouncementController::class)->except('show');
        Route::resource('pages', Admin\PageController::class)->only(['index', 'edit', 'update']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
        Route::resource('media', Admin\MediaController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('sections/{key}/edit', [Admin\SectionController::class, 'edit'])->name('sections.edit');
        Route::put('sections/{key}', [Admin\SectionController::class, 'update'])->name('sections.update');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

Route::get('/{page:slug}', PageController::class)
    ->where('page', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.show');
