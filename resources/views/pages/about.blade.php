@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>{{ $page->title }}</h1>
        @if($page->subtitle)<p class="page-hero__sub">{{ $page->subtitle }}</p>@endif
    </div>
</section>

@if(!empty($about['stats']))
<section class="section--flush">
    <div class="container">
        <div class="stats-row" data-reveal>
            @foreach($about['stats'] as $stat)
                <div class="stat">
                    <span class="stat__number"><span class="count-up" data-count-to="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] ?? '' }}</span>
                    <span class="stat__label">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
            <h2>{{ $about['heading'] ?? 'Why patients choose us' }}</h2>
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

@include('partials.booking-strip-cta')
@endsection
