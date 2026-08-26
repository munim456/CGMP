@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>{{ $page->title }}</h1>
        @if($page->subtitle)<p class="page-hero__sub">{{ $page->subtitle }}</p>@endif
    </div>
</section>

<section class="section">
    <div class="container narrow prose" data-reveal>
        {!! $page->body !!}
    </div>
</section>

@if(!empty($about['image']) || !empty($about['points']))
<section class="section section--tint">
    <div class="container split">
        <div class="split__media" data-reveal>
            @if(!empty($about['image']))
                <img src="{{ image_url($about['image']) }}" alt="Inside {{ setting('clinic_name') }}" loading="lazy" class="rounded-img">
            @endif
        </div>
        <div class="split__text" data-reveal>
            <h2>Why patients choose us</h2>
            <ul class="tick-list">
                @foreach($about['points'] ?? [] as $point)
                    <li><x-icon name="check-circle" class="w-5 h-5"/> {{ $point }}</li>
                @endforeach
            </ul>
            <a href="{{ route('booking') }}" class="btn btn--primary">Book online</a>
        </div>
    </div>
</section>
@endif

@if($doctors->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head section-head--center" data-reveal>
            <p class="eyebrow">Our team</p>
            <h2>Your doctors at {{ setting('clinic_name') }}</h2>
        </div>
        <div class="grid grid--2 doctor-grid doctor-grid--detailed">
            @foreach($doctors as $doctor)
                <article class="doctor-card doctor-card--row" data-reveal>
                    <div class="doctor-card__photo doctor-card__photo--sm">
                        @if($doctor->photo)
                            <img src="{{ image_url($doctor->photo) }}" alt="Photo of {{ $doctor->name }}" loading="lazy">
                        @else
                            <span class="avatar-initials">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                        @endif
                    </div>
                    <div>
                        <h3>{{ $doctor->name }}</h3>
                        @if($doctor->role)<p class="doctor-card__role">{{ $doctor->role }}</p>@endif
                        @if($doctor->qualifications)<p class="doctor-card__quals">{{ $doctor->qualifications }}</p>@endif
                        <div class="prose prose--sm">{!! $doctor->bio !!}</div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.booking-strip-cta')
@endsection
