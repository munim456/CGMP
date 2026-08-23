@extends('layouts.public')

@section('content')

<section class="hero" data-reveal-group>
    <div class="hero__bg" aria-hidden="true">
        @if($hero['image'] ?? null)
            <img src="{{ image_url($hero['image']) }}" alt="" loading="eager">
        @endif
        <div class="hero__overlay"></div>
    </div>
    <div class="container hero__inner">
        <div class="hero__content">
            @if(!empty($hero['badge_text']))
                <span class="chip chip--light hero__badge" data-reveal><x-icon name="check-circle" class="w-4 h-4"/> {{ $hero['badge_text'] }}</span>
            @endif
            <h1 data-reveal>{{ $hero['heading'] ?? 'Welcome to Cringila General Medical Practice' }}</h1>
            <p class="hero__sub" data-reveal>{{ $hero['subheading'] ?? '' }}</p>
            <div class="hero__actions" data-reveal>
                @if(!empty($hero['primary_button_text']))
                    <a href="{{ str_starts_with($hero['primary_button_link'] ?? '', 'http') ? $hero['primary_button_link'] : route('booking') }}"
                       @if(str_starts_with($hero['primary_button_link'] ?? '', 'http')) target="_blank" rel="noopener" @endif
                       class="btn btn--accent btn--lg">
                        <x-icon name="calendar-check" class="w-5 h-5"/> {{ $hero['primary_button_text'] }}
                    </a>
                @endif
                @if(!empty($hero['secondary_button_text']))
                    <a href="{{ str_contains(($hero['secondary_button_link'] ?? ''), '/') ? ($hero['secondary_button_link'] ?: '#services') : '#services' }}" class="btn btn--ghost btn--lg">
                        {{ $hero['secondary_button_text'] }} <x-icon name="arrow-right" class="w-5 h-5"/>
                    </a>
                @endif
            </div>
            <ul class="hero__trust" data-reveal>
                <li><x-icon name="clock" class="w-4 h-4"/> Open five days a week</li>
                <li><x-icon name="calendar-check" class="w-4 h-4"/> Same-day appointments</li>
                <li><x-icon name="users" class="w-4 h-4"/> Walk-ins welcome</li>
            </ul>
        </div>
    </div>
</section>

<section class="section section--tint home-blog" id="latest-news" aria-labelledby="home-blog-title">
    <div class="container">
        <div class="section-head" data-reveal>
            <div>
                <p class="eyebrow">Health news & clinic updates</p>
                <h2 id="home-blog-title">From our blog</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn--outline">View all posts <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>

        @if($latestPosts->isNotEmpty())
            <div class="grid grid--4 post-grid">
                @foreach($latestPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        @else
            <p class="muted" data-reveal>Articles are coming soon — check back shortly.</p>
        @endif
    </div>
</section>

@if(count($highlights))
<section class="section highlights" id="highlights" aria-label="Why choose us">
    <div class="container">
        <div class="grid grid--4 highlight-grid">
            @foreach($highlights as $item)
                <div class="highlight-card" data-reveal>
                    <span class="highlight-card__icon"><x-icon name="{{ $item['icon'] ?? 'activity' }}"/></span>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section about-home" id="about-preview">
    <div class="container split split--text-first">
        <div class="split__text" data-reveal>
            <p class="eyebrow">About the practice</p>
            <h2>{{ $about['heading'] ?? '' }}</h2>
            <div class="prose">{!! ($about['body'] ?? '') !!}</div>
            @if(!empty($about['points']))
                <ul class="tick-list">
                    @foreach($about['points'] as $point)
                        <li><x-icon name="check-circle" class="w-5 h-5"/> {{ $point }}</li>
                    @endforeach
                </ul>
            @endif
            <a href="{{ route('about') }}" class="btn btn--primary">Learn more about us <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
        <div class="split__media" data-reveal>
            @if(!empty($about['image']))
                <img src="{{ image_url($about['image']) }}" alt="Inside {{ setting('clinic_name') }}" loading="lazy" class="rounded-img">
            @else
                <div class="img-placeholder img-placeholder--tall"><x-icon name="image" class="w-12 h-12"/></div>
            @endif
        </div>
    </div>

    @if(!empty($about['stats']))
        <div class="container stats-row" data-reveal>
            @foreach($about['stats'] as $stat)
                <div class="stat">
                    <span class="stat__number"><span class="count-up" data-count-to="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] ?? '' }}</span>
                    <span class="stat__label">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section class="section section--tint doctors-home" id="our-doctors">
    <div class="container">
        <div class="section-head section-head--center" data-reveal>
            <p class="eyebrow">Our team</p>
            <h2>Meet our doctors</h2>
        </div>
        <div class="grid grid--{{ min(3, max(2, $doctors->count())) }} doctor-grid">
            @foreach($doctors as $doctor)
                <article class="doctor-card" data-reveal>
                    <div class="doctor-card__photo">
                        @if($doctor->photo)
                            <img src="{{ image_url($doctor->photo) }}" alt="Photo of {{ $doctor->name }}" loading="lazy">
                        @else
                            <span class="avatar-initials">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                        @endif
                    </div>
                    <h3>{{ $doctor->name }}</h3>
                    @if($doctor->role)<p class="doctor-card__role">{{ $doctor->role }}</p>@endif
                    @if($doctor->qualifications)<p class="doctor-card__quals">{{ $doctor->qualifications }}</p>@endif
                </article>
            @endforeach
        </div>
        <div class="center" data-reveal>
            <a href="{{ route('doctors') }}" class="btn btn--outline">Read doctor bios <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
    </div>
</section>

@if($testimonials->isNotEmpty())
<section class="section testimonials" aria-label="Patient feedback">
    <div class="container narrow">
        <div class="section-head section-head--center" data-reveal>
            <p class="eyebrow">Patient feedback</p>
            <h2>What our patients say</h2>
        </div>
        <div class="testimonial-slider" id="testimonial-slider" data-reveal>
            <button type="button" class="slider-btn slider-btn--prev" aria-label="Previous testimonial"><x-icon name="chevron-left" class="w-6 h-6"/></button>
            <div class="testimonial-track" id="testimonial-track">
                @foreach($testimonials as $testimonial)
                    <figure class="testimonial-slide">
                        <x-icon name="quote" class="w-8 h-8 testimonial-quote"/>
                        <blockquote>{{ $testimonial->content }}</blockquote>
                        <figcaption>
                            <strong>{{ $testimonial->name }}</strong>
                            @if($testimonial->context)<span>{{ $testimonial->context }}</span>@endif
                            <span class="stars" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                @for($i = 0; $i < $testimonial->rating; $i++)<x-icon name="star" class="w-4 h-4 star-filled"/>@endfor
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
            <button type="button" class="slider-btn slider-btn--next" aria-label="Next testimonial"><x-icon name="chevron-right" class="w-6 h-6"/></button>
            <div class="slider-dots" id="testimonial-dots" role="tablist" aria-label="Choose testimonial"></div>
        </div>
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
