@php($compact ??= false)
@php($accent = $post->category?->slug === 'health-advice' ? 'accent' : 'primary')
<article class="post-card post-card--accent-{{ $accent }} @if($compact) post-card--compact @endif" data-reveal>
    <a href="{{ route('blog.show', $post) }}" class="post-card__media" tabindex="-1" aria-hidden="true">
        @if($post->featured_image)
            <img src="{{ image_url($post->featured_image_thumb ?: $post->featured_image) }}" alt="{{ $post->featured_image_alt ?: $post->title }}" loading="lazy">
        @else
            <span class="post-card__placeholder"><x-icon name="newspaper" class="w-10 h-10"/></span>
        @endif
    </a>
    <div class="post-card__body">
        <div class="post-card__meta">
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="chip {{ $accent === 'accent' ? 'chip--accent' : 'chip--soft' }}">{{ $post->category->name }}</a>
            @endif
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j M Y') }}</time>
        </div>
        <h3 class="post-card__title"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
        @if($post->excerpt)
            <p class="post-card__excerpt">{{ $post->excerpt }}</p>
        @endif
        <a href="{{ route('blog.show', $post) }}" class="read-more">Read article <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>
</article>
