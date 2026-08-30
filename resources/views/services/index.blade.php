@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our services</h1>
        <p class="page-hero__sub">We cater to the health needs of your whole family. From pregnancy to retirement, here are the {{ count($serviceDirectory) }} primary medical services provided at {{ setting('clinic_name') }}.</p>
        <div class="stats-row stats-row--compact" data-reveal>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="{{ count($serviceDirectory) }}">0</span></span>
                <span class="stat__label">Services offered</span>
            </div>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="5">0</span></span>
                <span class="stat__label">Days open each week</span>
            </div>
        </div>
    </div>
</section>

<section class="section section--tint section--services-listing services-category services-category--primary">
    <div class="container">
        <div class="services-listing-card">
            <h2>Medical services directory</h2>
            <div class="services-accordion">
                @foreach($serviceDirectory as $item)
                    <details class="services-accordion__item">
                        <summary>
                            <span class="services-accordion__icon"><x-icon name="{{ $item->icon ?: 'stethoscope' }}" class="w-5 h-5"/></span>
                            <span class="services-accordion__title">{{ $item->title }}</span>
                            <x-icon name="chevron-down" class="w-5 h-5 services-accordion__chevron"/>
                        </summary>
                        <div class="services-accordion__body">
                            <p>{{ $item->body }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

@include('partials.booking-strip-cta')
@endsection
