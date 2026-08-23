@extends('layouts.public')

@section('metaTitle', 'Page not found (404)')

@section('content')
<section class="section error-page">
    <div class="container narrow center" data-reveal>
        <p class="error-code">404</p>
        <h1>We couldn't find that page</h1>
        <p class="lead">The page may have moved or the address might be incorrect.</p>
        <div class="hero__actions center">
            <a href="{{ route('home') }}" class="btn btn--primary btn--lg"><x-icon name="home" class="w-5 h-5"/> Back to home</a>
            <a href="{{ route('booking') }}" class="btn btn--accent btn--lg"><x-icon name="calendar-check" class="w-5 h-5"/> Book an appointment</a>
        </div>
    </div>
</section>
@endsection
