@php
    $siteName = setting('clinic_name');
    $titleTemplate = setting('meta_title_template', ':title | :site');

    $metaTitle = match(true) {
        isset($metaTitle) => $metaTitle,
        isset($post) && isset($post->meta_title) => $post->meta_title,
        isset($page) && $page->meta_title => $page->meta_title,
        request()->routeIs('home') => $siteName . ': ' . setting('tagline', 'Family GP in Cringila'),
        default => str_replace([':title', ':site'], [
            ucfirst(str_replace('-', ' ', request()->path() === '/' ? 'Home' : request()->path())),
            $siteName,
        ], $titleTemplate),
    };

    $metaDescription = $metaDescription
        ?? ($post->meta_description ?? null)
        ?? ($page->meta_description ?? null)
        ?? setting('meta_description_default')
        ?? 'Cringila General Medical Practice: caring GPs open five days a week. Same-day appointments and walk-ins welcome. Book online with HealthEngine.';
@endphp

@php
    $resolvedOgImage = $ogImage
        ?? (isset($post) ? ($post->og_image ?? $post->featured_image) : null)
        ?? setting('og_image_path');
@endphp

<meta name="description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 160) }}">
@if(!empty($isPreview))
    <meta name="robots" content="noindex, nofollow">
@endif
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="{{ isset($post) ? 'article' : 'website' }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 200) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ image_url($resolvedOgImage) }}">
@isset($post)
    @if($post->published_at)
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    @if($post->author)
        <meta property="article:author" content="{{ $post->author->name }}">
    @endif
    @if($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif
    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
@endisset
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 200) }}">

<title>{{ $metaTitle }}</title>

@if(setting('favicon_path'))
    <link rel="icon" href="{{ image_url(setting('favicon_path')) }}">
@endif

@include('partials.jsonld')
