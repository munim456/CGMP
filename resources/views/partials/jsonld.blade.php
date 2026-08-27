<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "MedicalClinic",
  "name": "{{ setting('clinic_name') }}",
  "url": "{{ config('app.url') }}",
  "telephone": "{{ setting('phone') }}",
  "email": "{{ setting('contact_email') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ setting('address_line1') }}",
    "addressLocality": "Wollongong",
    "addressRegion": "NSW",
    "postalCode": "2502",
    "addressCountry": "AU"
  },
  "openingHours": "{{ setting('opening_hours_schema') }}",
  "medicalSpecialty": ["GeneralPractice"]
}
</script>

@isset($post)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Article",
  "headline": {{ Illuminate\Support\Js::from($post->title) }},
  "description": {{ Illuminate\Support\Js::from($post->meta_description ?? $post->excerpt) }},
  "image": {{ Illuminate\Support\Js::from($post->featured_image ? image_url($post->featured_image) : null) }},
  "datePublished": "{{ $post->published_at?->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": {{ Illuminate\Support\Js::from($post->author->name ?? setting('clinic_name')) }}
  },
  "publisher": {
    "@type": "Organization",
    "name": {{ Illuminate\Support\Js::from(setting('clinic_name')) }},
    "logo": {
      "@type": "ImageObject",
      "url": {{ Illuminate\Support\Js::from(image_url(setting('logo_path'))) }}
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": {{ Illuminate\Support\Js::from(route('blog.show', $post)) }}
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": {{ Illuminate\Support\Js::from(route('home')) }}},
    {"@type": "ListItem", "position": 2, "name": "Blog", "item": {{ Illuminate\Support\Js::from(route('blog.index')) }}},
    {"@type": "ListItem", "position": 3, "name": {{ Illuminate\Support\Js::from($post->title) }}, "item": {{ Illuminate\Support\Js::from(route('blog.show', $post)) }}}
  ]
}
</script>
@endisset
