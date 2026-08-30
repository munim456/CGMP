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

            <div class="editorial-list">
                @foreach($posts as $i => $blogPost)
                    <article class="editorial-row @if($i % 2 === 1) editorial-row--reverse @endif @if($i === 0) editorial-row--first @endif" data-reveal>
                        <span class="editorial-row__index" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('blog.show', $blogPost) }}" class="editorial-row__media" tabindex="-1" aria-hidden="true">
                            @if($blogPost->featured_image)
                                <img src="{{ image_url($blogPost->featured_image_thumb ?: $blogPost->featured_image) }}" alt="{{ $blogPost->featured_image_alt ?: $blogPost->title }}" loading="lazy">
                            @else
                                <span class="editorial-row__placeholder"><x-icon name="newspaper" class="w-9 h-9"/></span>
                            @endif
                        </a>
                        <div class="editorial-row__body">
                            <p class="editorial-row__meta">
                                @if($blogPost->category)
                                    <span class="editorial-row__category">{{ $blogPost->category->name }}</span>
                                    <span class="editorial-row__dot" aria-hidden="true">&bull;</span>
                                @endif
                                <time datetime="{{ $blogPost->published_at->toDateString() }}">{{ $blogPost->published_at->format('j F Y') }}</time>
                            </p>
                            <h2 class="editorial-row__title"><a href="{{ route('blog.show', $blogPost) }}">{{ $blogPost->title }}</a></h2>
                            @if($blogPost->excerpt)
                                <p class="editorial-row__excerpt">{{ $blogPost->excerpt }}</p>
                            @endif
                            <a href="{{ route('blog.show', $blogPost) }}" class="editorial-row__link">Read the full article <x-icon name="arrow-right" class="w-4 h-4"/></a>
                        </div>
                    </article>
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
