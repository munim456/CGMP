@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our doctors</h1>
        <p class="page-hero__sub">Experienced GPs who take the time to listen.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        @if($doctors->isNotEmpty())
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
                            <h2>{{ $doctor->name }}</h2>
                            @if($doctor->role)<p class="doctor-card__role">{{ $doctor->role }}</p>@endif
                            @if($doctor->qualifications)<p class="doctor-card__quals">{{ $doctor->qualifications }}</p>@endif
                            <div class="prose prose--sm">{!! $doctor->bio !!}</div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="muted" data-reveal>Doctor profiles will be published shortly.</p>
        @endif
    </div>
</section>

@include('partials.booking-strip-cta')
@endsection
