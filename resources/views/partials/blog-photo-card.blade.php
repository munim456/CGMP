@php($size ??= 'md')
<a href="{{ route('blog.show', $post) }}" class="photo-card photo-card--{{ $size }}" data-reveal>
    @if($post->featured_image)
        <img src="{{ image_url($size === 'lg' ? $post->featured_image : ($post->featured_image_thumb ?: $post->featured_image)) }}" alt="{{ $post->featured_image_alt ?: $post->title }}" class="photo-card__img" loading="{{ $size === 'lg' ? 'eager' : 'lazy' }}">
    @else
        <span class="photo-card__placeholder"><x-icon name="newspaper" class="w-10 h-10"/></span>
    @endif
    <span class="photo-card__scrim" aria-hidden="true"></span>
    <span class="photo-card__content">
        @if($post->category)
            <span class="chip chip--light photo-card__chip">{{ $post->category->name }}</span>
        @endif
        <span class="photo-card__title">{{ $post->title }}</span>
        <span class="photo-card__meta">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j M Y') }}</time>
        </span>
    </span>
</a>
