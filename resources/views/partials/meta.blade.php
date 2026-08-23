@php
    $siteName = setting('clinic_name');
    $titleTemplate = setting('meta_title_template', ':title | :site');

    $metaTitle = match(true) {
        isset($metaTitle) => $metaTitle,
        isset($post) && isset($post->meta_title) => $post->meta_title,
        isset($page) && $page->meta_title => $page->meta_title,
        request()->routeIs('home') => $siteName . ' — ' . setting('tagline', 'Family GP in Cringila'),
        default => str_replace([':title', ':site'], [
            ucfirst(str_replace('-', ' ', request()->path() === '/' ? 'Home' : request()->path())),
            $siteName,
        ], $titleTemplate),
    };

    $metaDescription = $metaDescription
        ?? ($post->meta_description ?? null)
        ?? ($page->meta_description ?? null)
        ?? setting('meta_description_default')
        ?? 'Cringila General Medical Practice — caring GPs open five days a week. Same-day appointments and walk-ins welcome. Book online with HealthEngine.';
@endphp

<meta name="description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 160) }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 200) }}">
<meta property="og:url" content="{{ url()->current() }}">
@isset($ogImage)
    <meta property="og:image" content="{{ image_url($ogImage) }}">
@else
    <meta property="og:image" content="{{ image_url(setting('og_image_path')) }}">
@endisset
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($metaDescription, 200) }}">

<title>{{ $metaTitle }}</title>

@if(setting('favicon_path'))
    <link rel="icon" href="{{ image_url(setting('favicon_path')) }}">
@endif

@include('partials.jsonld')
