@extends('layouts.public')

@section('content')
<article class="section blog-single">
    <div class="container narrow">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('blog.index') }}">Blog</a>
            @if($post->category) / <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>@endif
        </nav>

        <header class="blog-single__header" data-reveal>
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="chip chip--soft">{{ $post->category->name }}</a>
            @endif
            <h1>{{ $post->title }}</h1>
            <div class="blog-single__meta">
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j F Y') }}</time>
                @if($post->author)
                    <span>· By {{ $post->author->name }}</span>
                @endif
            </div>
        </header>

        @if($post->featured_image)
            <figure class="blog-single__image" data-reveal>
                <img src="{{ image_url($post->featured_image) }}" alt="{{ $post->featured_image_alt ?: $post->title }}">
            </figure>
        @endif

        @if($post->excerpt)
            <p class="lead" data-reveal>{{ $post->excerpt }}</p>
        @endif

        <div class="prose prose--article" data-reveal>
            {!! $post->body !!}
        </div>

        @if($post->tags->isNotEmpty())
            <div class="tag-row" data-reveal>
                <x-icon name="file-text" class="w-4 h-4"/>
                @foreach($post->tags as $tag)
                    <span class="chip chip--soft">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <div class="share-row" data-reveal aria-label="Share this article">
            <span>Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on Facebook"><x-icon name="facebook" class="w-5 h-5"/></a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><x-icon name="external-link" class="w-5 h-5"/></a>
            <a href="https://wa.me/?text={{ urlencode($post->title . ' — ' . url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><x-icon name="message-square" class="w-5 h-5"/></a>
            <button type="button" data-copy-link="{{ url()->current() }}" aria-label="Copy link"><x-icon name="mail" class="w-5 h-5"/></button>
        </div>
    </div>
</article>

@if($related->isNotEmpty())
<section class="section section--tint">
    <div class="container">
        <div class="section-head" data-reveal><h2>Related articles</h2></div>
        <div class="grid grid--3 post-grid">
            @foreach($related as $relatedPost)
                @include('partials.post-card', ['post' => $relatedPost])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.booking-strip-cta')
@endsection
