@extends('layouts.public')

@section('title', $service->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($service->short_description ?: $service->description), 155))

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <p class="breadcrumb"><a href="{{ route('services.index') }}">Services</a> <x-icon name="chevron-right" class="w-4 h-4"/> {{ $service->title }}</p>
        <h1>{{ $service->title }}</h1>
        @if($service->short_description)<p class="page-hero__sub">{{ $service->short_description }}</p>@endif
    </div>
</section>

<section class="section">
    <div class="container service-detail">
        <div class="service-detail__body prose" data-reveal>
            {!! $service->description !!}
        </div>

        <aside class="service-detail__aside" data-reveal>
            <div class="aside-card">
                <h2>Book this service</h2>
                <p>Same-day appointments are available and walk-ins are welcome.</p>
                <a href="{{ route('booking') }}" class="btn btn--primary btn--block">Book an appointment</a>
                <a href="tel:{{ preg_replace('/\s+/', '', setting('phone', '')) }}" class="btn btn--ghost btn--block mt-1">Call {{ setting('phone') }}</a>
            </div>
        </aside>
    </div>
</section>

@if($others->isNotEmpty())
<section class="section section--alt">
    <div class="container">
        <h2 class="section-title">Other services</h2>
        <div class="grid grid--3 service-grid">
            @foreach($others as $other)
                <a href="{{ route('services.show', $other) }}" class="service-card">
                    <span class="service-card__icon"><x-icon name="{{ $other->icon ?: 'stethoscope' }}"/></span>
                    <h3>{{ $other->title }}</h3>
                    <span class="read-more">Learn more <x-icon name="arrow-right" class="w-4 h-4"/></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.booking-strip-cta')
@endsection
