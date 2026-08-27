@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Health blog</h1>
        <p class="page-hero__sub">Articles and updates from the team at {{ setting('clinic_name') }}.</p>
    </div>
</section>

<section class="section blog-index-section">
    <div class="container">
        <form method="GET" action="{{ route('blog.index') }}" class="blog-filters" role="search" data-reveal>
            <div class="search-field">
                <x-icon name="search" class="w-5 h-5"/>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search articles…" aria-label="Search articles">
            </div>
            @if(request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <button type="submit" class="btn btn--primary">Search</button>
        </form>

        <div class="chip-row" data-reveal>
            <a href="{{ route('blog.index', array_filter(['q' => request('q')])) }}"
               class="chip chip--filter {{ !request()->filled('category') ? 'chip--active' : '' }}">All topics</a>
            @foreach($categories as $category)
                <a href="{{ route('blog.index', array_filter(['category' => $category->slug, 'q' => request('q')])) }}"
                   class="chip chip--filter {{ request('category') === $category->slug ? 'chip--active' : '' }}">
                    {{ $category->name }} ({{ $category->posts_count }})
                </a>
            @endforeach
        </div>

        @if($posts->isNotEmpty())
            <p class="blog-results-count">
                {{ $posts->total() }} {{ \Illuminate\Support\Str::plural('article', $posts->total()) }}
                @if(request()->filled('category')) in {{ $categories->firstWhere('slug', request('category'))?->name }} @endif
                @if(request()->filled('q')) matching &ldquo;{{ request('q') }}&rdquo; @endif
            </p>
            <div class="grid grid--3 post-grid blog-post-grid">
                @foreach($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $posts->links() }}</div>
        @else
            <div class="empty-state" data-reveal>
                <x-icon name="newspaper" class="w-12 h-12"/>
                <h2>No articles found</h2>
                <p>Try a different search term or browse all posts.</p>
                <a href="{{ route('blog.index') }}" class="btn btn--primary">View all posts</a>
            </div>
        @endif
    </div>
</section>
@endsection
