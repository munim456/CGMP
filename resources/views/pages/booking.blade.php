@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Book an appointment</h1>
        <p class="page-hero__sub">Book online with HealthEngine, call the practice, or simply walk in.</p>
    </div>
</section>

<section class="section">
    <div class="container booking-layout">
        <div class="booking-widget" data-reveal>
            <h2><x-icon name="calendar-check" class="w-6 h-6"/> Book online</h2>
            @if(setting('healthengine_embed'))
                <div class="booking-embed">
                    {!! setting('healthengine_embed') !!}
                </div>
            @elseif(setting('healthengine_url'))
                <p>Choose a suitable time with our online booking partner, HealthEngine.</p>
                <a href="{{ setting('healthengine_url') }}" target="_blank" rel="noopener" class="btn btn--accent btn--lg">
                    <x-icon name="external-link" class="w-5 h-5"/> Open HealthEngine booking
                </a>
                <p class="muted mt-2"><small>Opens in a new tab on the secure HealthEngine website.</small></p>
            @else
                <p>Online booking is being connected. Please call the practice to arrange your appointment.</p>
            @endif
        </div>

        <aside class="booking-aside" data-reveal>
            <div class="info-card">
                <h3><x-icon name="phone" class="w-5 h-5"/> Phone bookings</h3>
                <p>Prefer to talk to us? Call during opening hours and our reception team will help.</p>
                <a href="tel:{{ preg_replace('/\s+/', '', setting('phone', '')) }}" class="btn btn--primary">{{ setting('phone') }}</a>
            </div>
            <div class="info-card">
                <h3><x-icon name="users" class="w-5 h-5"/> Walk-ins welcome</h3>
                <p>{!! setting('walk_in_note', 'Walk-in appointments are welcome; patients with bookings are seen first.') !!}</p>
            </div>
            <div class="info-card info-card--warning">
                <h3><x-icon name="alert-triangle" class="w-5 h-5"/> Emergencies</h3>
                <p>{{ setting('emergency_note', 'In a medical emergency, call 000 immediately.') }}</p>
            </div>
        </aside>
    </div>
</section>
@endsection
