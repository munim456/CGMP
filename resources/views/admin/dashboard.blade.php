@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="stat-cards">
    <a href="{{ route('admin.posts.index') }}" class="stat-card">
        <x-icon name="newspaper" class="w-6 h-6"/>
        <span class="stat-card__num">{{ $postCount }}</span>
        <span class="stat-card__label">Blog posts</span>
        <small>{{ $publishedCount }} published</small>
    </a>
    <a href="{{ route('admin.messages.index') }}" class="stat-card stat-card--alert">
        <x-icon name="message-square" class="w-6 h-6"/>
        <span class="stat-card__num">{{ $unreadCount }}</span>
        <span class="stat-card__label">Unread messages</span>
        <small>{{ \App\Models\ContactMessage::count() }} total</small>
    </a>
    <a href="{{ route('admin.doctors.index') }}" class="stat-card">
        <x-icon name="user-round" class="w-6 h-6"/>
        <span class="stat-card__num">{{ $doctorCount }}</span>
        <span class="stat-card__label">Doctors</span>
    </a>
    <a href="{{ route('admin.announcements.index') }}" class="stat-card">
        <x-icon name="megaphone" class="w-6 h-6"/>
        <span class="stat-card__num">{{ $activeAnnouncements }}</span>
        <span class="stat-card__label">Active announcements</span>
    </a>
</div>

<div class="admin-columns">
    <section class="admin-panel">
        <div class="admin-panel__head">
            <h2>Recent messages</h2>
            <a href="{{ route('admin.messages.index') }}">View all</a>
        </div>
        @if($unreadMessages->isNotEmpty())
            <ul class="mini-list">
                @foreach($unreadMessages as $msg)
                    <li>
                        <div>
                            <strong>{{ $msg->name }}</strong>
                            <p>{{ \Illuminate\Support\Str::limit($msg->message, 90) }}</p>
                        </div>
                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn--outline btn--sm">Open</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="muted p-4">No unread messages — the inbox is clear.</p>
        @endif
    </section>

    <section class="admin-panel">
        <div class="admin-panel__head">
            <h2>Recently edited posts</h2>
            <a href="{{ route('admin.posts.create') }}">New post</a>
        </div>
        @if($recentPosts->isNotEmpty())
            <ul class="mini-list mini-list--rows">
                @foreach($recentPosts as $post)
                    <li>
                        <div>
                            <strong><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></strong>
                            <p>{{ $post->status === 'published' ? 'Published ' . $post->published_at?->format('j M Y') : ucfirst($post->status) }}@if($post->category) · {{ $post->category->name }}@endif</p>
                        </div>
                        <span class="badge {{ $post->status === 'published' ? 'badge--success' : 'badge--neutral' }}">{{ $post->status }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="muted p-4">No posts yet. <a href="{{ route('admin.posts.create') }}">Write your first post →</a></p>
        @endif
    </section>
</div>

<section class="admin-panel">
    <div class="admin-panel__head"><h2>Quick actions</h2></div>
    <div class="quick-links">
        <a href="{{ route('admin.sections.edit', 'hero') }}"><x-icon name="image"/> Edit hero section</a>
        <a href="{{ route('admin.settings.edit') }}"><x-icon name="phone"/> Update phone & hours</a>
        <a href="{{ route('admin.settings.edit') }}"><x-icon name="calendar-check"/> HealthEngine booking link</a>
        <a href="{{ route('admin.announcements.create') }}"><x-icon name="megaphone"/> Add announcement</a>
        <a href="{{ route('admin.media.index') }}"><x-icon name="upload"/> Upload images</a>
        <a href="{{ route('admin.services.create') }}"><x-icon name="stethoscope"/> Add a service</a>
    </div>
</section>
@endsection
