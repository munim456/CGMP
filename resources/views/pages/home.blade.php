@extends('layouts.public')

@section('content')

<section class="hero-photo">
    <div class="hero-photo__bg" aria-hidden="true">
        <img src="{{ image_url($hero['image'] ?? null, 'media/placeholders/hero-bg.jpg') }}" alt="" loading="eager">
    </div>
    <div class="container hero-photo__wrap">
        <div class="hero-card">
            <h1>Welcome to {{ setting('clinic_name') }}</h1>

            <p class="hero-card__label">Contact us</p>
            <p>{{ setting('address_line1') }}<br>{{ setting('address_suburb') }}</p>
            <p><a href="tel:{{ preg_replace('/\s+/', '', setting('phone', '')) }}">{{ setting('phone') }}</a></p>

            <p class="hero-card__label">Opening hours</p>
            @foreach(preg_split('/\r\n|\r|\n/', trim(setting('opening_hours'))) as $line)
                @if(trim($line) !== '')<p class="hero-card__hours">{{ trim($line) }}</p>@endif
            @endforeach

            <a href="{{ route('booking') }}" class="btn btn--primary btn--lg hero-card__book">
                <x-icon name="calendar-check" class="w-5 h-5"/> Book online</a>
        </div>
    </div>
</section>

<section class="section intro-block">
    <div class="container">
        <div class="prose prose--intro">
            {!! $about['body'] !!}
        </div>
        <p class="intro-note">If you are experiencing any acute respiratory symptoms, please wear a mask
            and let our reception team know when you arrive.</p>
    </div>
</section>

@foreach($panels as $panel)
    <section class="media-panel @if($loop->odd) media-panel--rev @endif">
        <div class="media-panel__img">
            <img src="{{ image_url($panel->image) }}" alt="@if($panel->title){{ $panel->title }}@endif" loading="lazy">
        </div>
        <div class="media-panel__body">
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

<section class="media-panel">
    <div class="media-panel__img">
        <img src="{{ image_url('media/placeholders/welcome-side.jpg') }}" alt="Doctor talking with a patient" loading="lazy">
    </div>
    <div class="media-panel__body">
        <h2>Our Doctors</h2>
        <div class="prose">Our doctors are experienced in every aspect of family health and can help you
            at any stage of life. Some also have extra qualifications and areas of special interest,
            including women's health, men's health, mental health and chronic disease management.</div>
        <a href="{{ route('doctors') }}" class="btn btn--outline">Read Doctor Bios <x-icon name="arrow-right" class="w-4 h-4"/></a>
    </div>
</section>

<section class="section section--tint doctors-home" id="our-doctors">
    <div class="container">
        <div class="section-head section-head--center">
            <h2>Meet our doctors</h2>
        </div>
        <div class="grid grid--{{ min(3, max(2, $doctors->count())) }} doctor-grid">
            @foreach($doctors as $doctor)
                <article class="doctor-card">
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
        <div class="center">
            <a href="{{ route('doctors') }}" class="btn btn--outline">Read doctor bios <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
    </div>
</section>

@if($testimonials->isNotEmpty())
<section class="section testimonials" aria-label="Patient feedback">
    <div class="container narrow">
        <div class="section-head section-head--center">
            <h2>What our patients say</h2>
        </div>
        <div class="testimonial-slider" id="testimonial-slider">
            <button type="button" class="slider-btn slider-btn--prev" aria-label="Previous testimonial"><x-icon name="chevron-left" class="w-6 h-6"/></button>
            <div class="testimonial-track" id="testimonial-track">
                @foreach($testimonials as $testimonial)
                    <figure class="testimonial-slide">
                        <blockquote>{{ $testimonial->content }}</blockquote>
                        <figcaption>
                            <strong>{{ $testimonial->name }}</strong>
                            @if($testimonial->context)<span>{{ $testimonial->context }}</span>@endif
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
    <div class="container booking-strip__inner">
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
