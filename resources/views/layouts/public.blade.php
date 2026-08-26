<!DOCTYPE html>
<html lang="en-AU" data-palette="{{ setting('palette') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/aileron@5/index.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/aileron@5/300.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/aileron@5/600.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/aileron@5/700.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {!! setting('analytics_code') !!}
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>

<div class="a11y-toolbar">
    <div class="container a11y-toolbar__inner">
        <span class="a11y-toolbar__label"><x-icon name="accessibility" class="w-4 h-4"/> Accessibility:</span>
        <button type="button" id="font-smaller">Smaller</button>
        <button type="button" id="font-larger">Larger</button>
        <button type="button" id="print-page"><x-icon name="printer" class="w-4 h-4"/> Print</button>
    </div>
</div>

<header class="site-header" id="site-header">
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ setting('clinic_name') }} home">
            @if(setting('logo_path'))
                <img src="{{ image_url(setting('logo_path')) }}" alt="{{ setting('clinic_name') }} logo" class="brand__logo">
            @else
                <span class="brand__mark" aria-hidden="true"><x-icon name="briefcase-medical"/></span>
                <span class="brand__text">
                    <strong>{{ setting('clinic_name') }}</strong>
                    <small>{{ setting('tagline') }}</small>
                </span>
            @endif
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Main navigation">
            <ul>
                <li><a href="{{ route('booking') }}" class="nav-book-pill"><x-icon name="calendar-check" class="w-5 h-5"/> Book Online</a></li>
                <li><a href="{{ route('doctors') }}">Our Doctors</a></li>
                <li><a href="{{ route('fees') }}">Fees &amp; Information</a></li>
                <li><a href="{{ route('services.index') }}">Our Services</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
            </ul>
        </nav>

        <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="main-nav" aria-label="Open menu">
            <x-icon name="menu" class="w-6 h-6 icon--open"/>
            <x-icon name="x" class="w-6 h-6 icon--close"/>
        </button>
    </div>
</header>

<div class="header-info-bar">
    <div class="container header-info-bar__inner">
        <a href="{{ route('booking') }}" class="header-info-bar__link">
            <x-icon name="calendar-check" class="w-5 h-5"/> Book online</a>
        <span class="header-info-bar__sep" aria-hidden="true">|</span>
        <a href="tel:{{ tel_url(setting('phone')) }}" class="header-info-bar__link">
            <x-icon name="phone" class="w-5 h-5"/> Call us</a>
    </div>
</div>

@foreach($announcements ?? [] as $announcement)
    <div class="notice notice--{{ $announcement->type }}" role="status"
         data-dismissible data-notice-id="notice-{{ $announcement->id }}-{{ $announcement->updated_at->timestamp }}">
        <div class="container notice__inner">
            <p><x-icon name="{{ $announcement->type === 'warning' ? 'alert-triangle' : 'info' }}" class="w-5 h-5"/>
                @if($announcement->title)<strong>{{ $announcement->title }}:</strong> @endif{{ $announcement->message }}</p>
            <button type="button" class="notice__close" aria-label="Dismiss announcement" data-dismiss-notice>
                <x-icon name="x" class="w-4 h-4"/>
            </button>
        </div>
    </div>
@endforeach

<main id="main">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__col">
            <h3>Contact us</h3>
            <ul class="footer-contact">
                <li><x-icon name="map-pin" class="w-5 h-5"/>
                    <span>{{ setting('address_line1') }}<br>{{ setting('address_suburb') }}</span></li>
                <li><x-icon name="phone" class="w-5 h-5"/>
                    <a href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a></li>
                <li><x-icon name="mail" class="w-5 h-5"/>
                    <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></li>
            </ul>
            <div class="footer-socials">
                @if(setting('facebook_url'))
                    <a href="{{ setting('facebook_url') }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="facebook" class="w-5 h-5"/></a>
                @endif
                @if(setting('instagram_url'))
                    <a href="{{ setting('instagram_url') }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="instagram" class="w-5 h-5"/></a>
                @endif
                @if(setting('youtube_url'))
                    <a href="{{ setting('youtube_url') }}" target="_blank" rel="noopener" aria-label="YouTube"><x-icon name="youtube" class="w-5 h-5"/></a>
                @endif
            </div>
        </div>

        <div class="site-footer__col">
            <h3>Opening hours</h3>
            <pre class="footer-hours">{{ setting('opening_hours') }}</pre>
            @if(setting('emergency_note'))
                <p class="footer-emergency">{{ setting('emergency_note') }}</p>
            @endif
        </div>

        <div class="site-footer__col">
            <h3>Quick links</h3>
            <ul class="footer-links">
                <li><a href="{{ route('about') }}">About the practice</a></li>
                <li><a href="{{ route('services.index') }}">Our services</a></li>
                <li><a href="{{ route('doctors') }}">Our doctors</a></li>
                <li><a href="{{ route('fees') }}">Fees &amp; information</a></li>
                <li><a href="{{ route('blog.index') }}">Health blog</a></li>
                <li><a href="{{ route('booking') }}">Book an appointment</a></li>
                <li><a href="{{ route('contact') }}">Contact us</a></li>
                <li><a href="{{ route('pages.privacy') }}">Privacy policy</a></li>
            </ul>
        </div>

        <div class="site-footer__col site-footer__col--map">
            <h3>Find us</h3>
            <iframe class="footer-map" loading="lazy"
                src="{{ setting('google_map_embed') }}"
                title="Map showing {{ setting('clinic_name') }} location"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container site-footer__bottom-inner">
            <p>{!! setting('footer_text') !!}</p>
            <p>&copy; {{ now()->year }} {{ setting('copyright_text') ?: setting('clinic_name') }}</p>
        </div>
    </div>
</footer>

<div class="mobile-cta" aria-label="Quick actions">
    <a href="tel:{{ tel_url(setting('phone')) }}" class="mobile-cta__btn mobile-cta__btn--call">
        <x-icon name="phone" class="w-5 h-5"/> Call us</a>
    <a href="{{ route('booking') }}" class="mobile-cta__btn mobile-cta__btn--book">
        <x-icon name="calendar-check" class="w-5 h-5"/> Book online</a>
</div>

<script>document.documentElement.classList.add('js');</script>
</body>
</html>
