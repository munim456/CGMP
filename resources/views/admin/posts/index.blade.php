@extends('layouts.admin')

@section('page-title', 'Blog posts')

@section('content')
<div class="admin-toolbar">
    <form method="GET" class="admin-search">
        <x-icon name="search" class="w-4 h-4"/>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search posts…">
        <select name="status" aria-label="Filter by status">
            <option value="">All statuses</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
        </select>
        <select name="category" aria-label="Filter by category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn--outline btn--sm">Filter</button>
    </form>
    <a href="{{ route('admin.posts.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> New post</a>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Title</th><th>Category</th><th>Status</th><th>Date</th><th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                <td>
                    <strong><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></strong>
                    @if($post->tags->isNotEmpty())
                        <p class="table-sub">{{ $post->tags->pluck('name')->implode(', ') }}</p>
                    @endif
                </td>
                <td>{{ $post->category?->name ?? '—' }}</td>
                <td><span class="badge {{ $post->status === 'published' ? 'badge--success' : ($post->status === 'scheduled' ? 'badge--warning' : 'badge--neutral') }}">{{ $post->status }}</span></td>
                <td>
                    @if($post->status === 'scheduled' && $post->scheduled_for)
                        Publishes {{ $post->scheduled_for->format('j M Y, g:ia') }}
                    @else
                        {{ $post->published_at?->format('j M Y') ?? '—' }}
                    @endif
                </td>
                <td class="table-actions">
                    <form method="POST" action="{{ route('admin.posts.toggle-publish', $post) }}">
                        @csrf
                        <button type="submit" class="icon-btn" title="{{ $post->status === 'published' ? 'Unpublish' : 'Publish' }}">
                            <x-icon name="{{ $post->status === 'published' ? 'eye' : 'check-circle' }}"/>
                        </button>
                    </form>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="icon-btn" title="Edit"><x-icon name="pencil"/></a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Delete this post permanently?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No posts found. <a href="{{ route('admin.posts.create') }}">Create your first post →</a></td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $posts->links() }}
@endsection
