@extends('layouts.admin')

@section('page-title', 'Categories & tags')

@section('content')
<div class="admin-columns">
    <section class="admin-panel">
        <div class="admin-panel__head"><h2>Categories</h2></div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="inline-form">
            @csrf
            <input type="text" name="name" placeholder="New category name" required maxlength="80">
            <button type="submit" class="btn btn--primary btn--sm"><x-icon name="plus" class="w-4 h-4"/> Add</button>
        </form>
        <ul class="edit-list">
            @foreach($categories as $category)
                <li>
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="inline-form inline-form--grow">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" maxlength="80" aria-label="Category name">
                        <span class="muted">{{ $category->posts_count }} posts · /{{ $category->slug }}</span>
                    </form>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Delete this category?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__head"><h2>Tags</h2></div>
        <form method="POST" action="{{ route('admin.tags.store') }}" class="inline-form">
            @csrf
            <input type="text" name="name" placeholder="New tag name" required maxlength="60">
            <button type="submit" class="btn btn--primary btn--sm"><x-icon name="plus" class="w-4 h-4"/> Add</button>
        </form>
        <ul class="edit-list">
            @foreach($tags as $tag)
                <li>
                    <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="inline-form inline-form--grow">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $tag->name }}" maxlength="60" aria-label="Tag name">
                        <span class="muted">{{ $tag->posts_count }} posts</span>
                    </form>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" data-confirm="Delete this tag?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </li>
            @endforeach
        </ul>
    </section>
</div>
@endsection
