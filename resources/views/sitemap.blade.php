<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach(['about', 'doctors', 'booking', 'contact'] as $static)
        <url><loc>{{ route($static === 'booking' ? 'booking' : ($static === 'about' ? 'about' : ($static === 'doctors' ? 'doctors' : 'contact'))) }}</loc><priority>0.8</priority></url>
    @endforeach
    <url><loc>{{ route('services.index') }}</loc><priority>0.8</priority></url>
    @foreach($services as $service)
        <url><loc>{{ route('services.show', $service) }}</loc><lastmod>{{ optional($service->updated_at)->toAtomString() }}</lastmod></url>
    @endforeach
    <url><loc>{{ route('blog.index') }}</loc><priority>0.7</priority></url>
    @foreach($posts as $post)
        <url><loc>{{ route('blog.show', $post) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
    @foreach($pages as $page)
        @continue(in_array($page->slug, ['about']))
        <url><loc>{{ route('pages.show', ['page' => $page->slug]) }}</loc><lastmod>{{ $page->updated_at->toAtomString() }}</lastmod></url>
    @endforeach
</urlset>
