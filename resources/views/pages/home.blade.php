@extends('layouts.public')

@push('scripts')
    @viteReactRefresh
    @vite(['resources/js/react-entry.jsx'])
@endpush

@section('content')

<section class="hero-photo">
    <div class="hero-photo__bg" aria-hidden="true">
        <img src="{{ image_url($hero['image'] ?? null, 'storage/media/placeholders/hero-bg.jpg') }}" alt="" loading="eager">
    </div>
    <div class="container hero-photo__wrap">
        <div class="hero-card">
            <h1>Welcome to {{ setting('clinic_name') }}</h1>

            <p class="hero-card__label">Contact us</p>
            <p>{{ setting('address_line1') }}<br>{{ setting('address_suburb') }}</p>
            <p><a href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a></p>

            <p class="hero-card__label">Opening hours</p>
            @foreach(preg_split('/\r\n|\r|\n/', trim(setting('opening_hours'))) as $line)
                @if(trim($line) !== '')<p class="hero-card__hours">{{ trim($line) }}</p>@endif
            @endforeach

            <a href="{{ route('booking') }}" class="btn btn--primary btn--lg hero-card__book">
                <x-icon name="calendar-check" class="w-5 h-5"/> Book online</a>
        </div>
    </div>
</section>

@if($latestPosts->isNotEmpty())
<section class="section" aria-label="Latest from the practice">
    <div class="container">
        <div class="section-head" data-reveal>
            <h2>Latest from the practice</h2>
            <a href="{{ route('blog.index') }}" class="read-more">View all articles <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
        <div class="grid grid--3 post-grid">
            @foreach($latestPosts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section intro-block">
    <div class="container" data-reveal>
        <div class="prose prose--intro">
            {!! $about['body'] !!}
        </div>
        <p class="intro-note">If you are experiencing any acute respiratory symptoms, please wear a mask
            and let our reception team know when you arrive.</p>
    </div>
</section>

@foreach($panels as $panel)
    <section class="media-panel @if($loop->odd) media-panel--rev @endif">
        <div class="media-panel__img" data-reveal>
            <img src="{{ image_url($panel->image) }}" alt="@if($panel->title){{ $panel->title }}@endif" loading="lazy">
        </div>
        <div class="media-panel__body" data-reveal>
            <h2>{{ $panel->title ?? setting('clinic_name') }}</h2>
            <div class="prose">{!! nl2br(e($panel->message)) !!}</div>
            @if($panel->button_text && $panel->button_url)
                @php($url = $panel->button_url)
                <a href="{{ $url }}"
                   @if(str_starts_with($url, 'http')) target="_blank" rel="noopener" @endif
                   class="btn btn--outline">{{ $panel->button_text }}</a>
            @endif
        </div>
    </section>
@endforeach

@if($testimonials->isNotEmpty())
<section class="section testimonials" aria-label="Patient feedback">
    <div class="container narrow">
        <div class="section-head section-head--center" data-reveal>
            <h2>What our patients say</h2>
        </div>
        <div data-reveal data-react-root="testimonials" data-testimonials="{{ $testimonials->map(fn ($t) => [
            'content' => $t->content,
            'name' => $t->name,
            'context' => $t->context,
        ])->toJson() }}"></div>
    </div>
</section>
@endif

<section class="booking-strip" id="book">
    <div class="container booking-strip__inner" data-reveal>
        <div>
            <h2>{{ $bookingStrip['heading'] ?? 'Ready to see a doctor?' }}</h2>
            <p>{{ $bookingStrip['text'] ?? '' }}</p>
        </div>
        <a href="{{ route('booking') }}" class="btn btn--light btn--lg">
            <x-icon name="calendar-check" class="w-5 h-5"/> {{ $bookingStrip['button_text'] ?? 'Book online with HealthEngine' }}
        </a>
    </div>
</section>

@endsection
