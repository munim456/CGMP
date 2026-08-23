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
@endsection
