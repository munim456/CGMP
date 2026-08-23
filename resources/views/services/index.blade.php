@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our services</h1>
        <p class="page-hero__sub">Comprehensive primary care for the whole family.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid--3 service-grid">
            @foreach($services as $service)
                <a href="{{ route('services.show', $service) }}" class="service-card" data-reveal>
                    <span class="service-card__icon"><x-icon name="{{ $service->icon ?: 'stethoscope' }}"/></span>
                    <h2>{{ $service->title }}</h2>
                    <p>{{ \Illuminate\Support\Str::limit($service->short_description, 140) }}</p>
                    <span class="read-more">Learn more <x-icon name="arrow-right" class="w-4 h-4"/></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

@include('partials.booking-strip-cta')
@endsection
