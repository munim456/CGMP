<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('layout-title', 'Admin') · {{ setting('clinic_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@600;700;800&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="admin-body {{ $bodyClass ?? '' }}">
@hasSection('layout-content')
    @yield('layout-content')
@else
<div class="admin-shell">
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-sidebar__brand">
            <span class="brand__mark brand__mark--light"><x-icon name="briefcase-medical"/></span>
            <div>
                <strong>{{ \Illuminate\Support\Str::limit(setting('clinic_name'), 26) }}</strong>
                <small>Website admin</small>
            </div>
        </div>

        <nav class="admin-nav" aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <x-icon name="layout-dashboard"/> Dashboard</a>
            <p class="admin-nav__group">Content</p>
            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'is-active' : '' }}">
                <x-icon name="newspaper"/> Blog posts
                <span class="nav-badge">{{ App\Models\Post::count() }}</span></a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'is-active' : '' }}">
                <x-icon name="file-text"/> Categories & tags</a>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('services.*') ? 'is-active' : '' }}">
                <x-icon name="stethoscope"/> Services</a>
            <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'is-active' : '' }}">
                <x-icon name="user-round"/> Doctors</a>
            <p class="admin-nav__group">Website sections</p>
            <a href="{{ route('admin.sections.edit', 'hero') }}" class="{{ request()->routeIs('sections.edit') && request()->route('key') === 'hero' ? 'is-active' : '' }}">
                <x-icon name="image"/> Hero section</a>
            <a href="{{ route('admin.sections.edit', 'highlights') }}" class="{{ request()->routeIs('sections.edit') && request()->route('key') === 'highlights' ? 'is-active' : '' }}">
                <x-icon name="heart-pulse"/> Home highlights</a>
            <a href="{{ route('admin.sections.edit', 'about') }}" class="{{ request()->routeIs('sections.edit') && request()->route('key') === 'about' ? 'is-active' : '' }}">
                <x-icon name="home"> </x-icon> About block & stats</a>
            <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('announcements.*') ? 'is-active' : '' }}">
                <x-icon name="megaphone"/> Announcements
                @if(App\Models\Announcement::where('is_active', true)->count())
                    <span class="nav-dot" title="Active"></span>@endif</a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('testimonials.*') ? 'is-active' : '' }}">
                <x-icon name="star"/> Testimonials</a>
            <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('pages.*') ? 'is-active' : '' }}">
                <x-icon name="file-text"/> Static pages</a>
            <p class="admin-nav__group">Practice</p>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'is-active' : '' }}">
                <x-icon name="message-square"/> Messages
                @if($unreadCount = App\Models\ContactMessage::where('is_read', false)->count())
                    <span class="nav-badge nav-badge--alert">{{ $unreadCount }}</span>@endif</a>
            <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('media.*') ? 'is-active' : '' }}">
                <x-icon name="image"> </x-icon> Media library</a>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                <x-icon name="settings"/> Site settings</a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                <x-icon name="settings"> </x-icon> My account</a>
        </nav>

        <div class="admin-sidebar__foot">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout"><x-icon name="log-out"/> Sign out</button>
            </form>
            <a href="{{ route('home') }}" target="_blank" class="view-site"><x-icon name="external-link"/> View website</a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button type="button" class="admin-burger" id="admin-burger" aria-label="Toggle menu" aria-expanded="false">
                <x-icon name="menu" class="w-6 h-6"/>
            </button>
            <h1 class="admin-topbar__title">@yield('page-title', 'Dashboard')</h1>
            <div class="admin-topbar__right">
                <a href="{{ route('admin.posts.create') }}" class="btn btn--primary btn--sm"><x-icon name="plus" class="w-4 h-4"/> New post</a>
                <span class="admin-user">{{ auth()->user()?->name }}</span>
            </div>
        </header>

        <main class="admin-content">
            @if(session('status'))
                <div class="alert alert--success" role="status">
                    <x-icon name="check-circle" class="w-5 h-5"/> {{ session('status') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert--danger" role="status">
                    <x-icon name="alert-triangle" class="w-5 h-5"/> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endif
</body>
</html>
