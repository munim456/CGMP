@extends('layouts.public')

@push('scripts')
    @viteReactRefresh
    @vite(['resources/js/react-entry.jsx'])
@endpush

@section('content')

<section class="hero-photo">
    <div class="hero-photo__bg" aria-hidden="true">
        <img src="{{ image_url($hero['image'] ?? null, 'storage/media/placeholders/hero-bg.jpg') }}" alt="" loading="eager">
    </div>
    <div class="container hero-photo__wrap">
        <div class="hero-card">
            <h1>Welcome to {{ setting('clinic_name') }}</h1>

            <p class="hero-card__label">Contact us</p>
            <p>{{ setting('address_line1') }}<br>{{ setting('address_suburb') }}</p>
            <p><a href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a></p>

            <p class="hero-card__label">Opening hours</p>
            @foreach(preg_split('/\r\n|\r|\n/', trim(setting('opening_hours'))) as $line)
                @if(trim($line) !== '')<p class="hero-card__hours">{{ trim($line) }}</p>@endif
            @endforeach

            <a href="{{ route('booking') }}" class="btn btn--primary btn--lg hero-card__book">
                <x-icon name="calendar-check" class="w-5 h-5"/> Book online</a>
        </div>
    </div>
</section>

@if($latestPosts->isNotEmpty())
<section class="section home-blog" aria-label="Latest from the practice">
    <div class="container">
        <div class="section-head" data-reveal>
            <h2>Latest from the practice</h2>
            <a href="{{ route('blog.index') }}" class="read-more">View all articles <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
        <div class="grid grid--3 post-grid post-grid--compact">
            @foreach($latestPosts as $post)
                @include('partials.post-card', ['post' => $post, 'compact' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

@if(count($highlights))
<section class="section highlights" id="highlights" aria-label="Why choose us">
    <div class="container">
        <div class="hl-strip" data-reveal>
            @foreach($highlights as $item)
                <div class="hl-strip__item">
                    <span class="hl-strip__icon"><x-icon name="{{ $item['icon'] ?? 'activity' }}" class="w-5 h-5"/></span>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section intro-block">
    <div class="container" data-reveal>
        <div class="prose prose--intro">
            {!! $about['body'] !!}
        </div>
        <p class="intro-note">If you are experiencing any acute respiratory symptoms, please wear a mask
            and let our reception team know when you arrive.</p>

        @if(!empty($about['stats']))
            <div class="stats-row" data-reveal>
                @foreach($about['stats'] as $stat)
                    @continue(empty($stat['label']))
                    <div class="stat">
                        <span class="stat__number"><span class="count-up" data-count-to="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] ?? '' }}</span>
                        <span class="stat__label">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if($doctors->isNotEmpty())
<section class="section section--tint doctors-home" id="our-doctors" aria-label="Meet our doctors">
    <div class="container">
        <div class="section-head section-head--center">
            <h2>Meet our doctors</h2>
            <p class="section-head__lead">Experienced in every aspect of family health, with some doctors
                holding extra qualifications in women's health, men's health, mental health and chronic
                disease management.</p>
        </div>

        <div class="dr-spotlight" data-doctor-spotlight data-reveal>
            @foreach($doctors as $doctor)
                <article class="dr-spotlight__profile @if($loop->first) is-active @endif"
                         id="dr-panel-{{ $doctor->id }}" data-doctor-panel="{{ $doctor->id }}"
                         role="tabpanel" aria-labelledby="dr-tab-{{ $doctor->id }}">
                    <div class="dr-spotlight__frame">
                        <div class="dr-spotlight__photo">
                            @if($doctor->photo)
                                <img src="{{ image_url($doctor->photo) }}" alt="Photo of {{ $doctor->name }}" loading="lazy">
                            @else
                                <span class="avatar-initials avatar-initials--lg">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="dr-spotlight__info">
                        <h3>{{ $doctor->name }}</h3>
                        @if($doctor->role)<p class="dr-spotlight__role">{{ $doctor->role }}</p>@endif
                        @if($doctor->qualifications)
                            <p class="dr-spotlight__quals">{{ $doctor->qualifications }}</p>
                        @endif
                        @if($doctor->special_interests)
                            <div class="dr-spotlight__tags">
                                @foreach(array_slice(array_filter(array_map('trim', explode(',', $doctor->special_interests))), 0, 4) as $interest)
                                    <span class="chip chip--soft">{{ $interest }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($doctor->bio)
                            <p class="dr-spotlight__bio">{{ \Illuminate\Support\Str::limit(strip_tags($doctor->bio), 130) }}</p>
                        @endif
                        <a href="{{ route('booking') }}" class="btn btn--primary">
                            <x-icon name="calendar-check" class="w-5 h-5"/> Book with {{ $doctor->name }}
                        </a>
                    </div>
                </article>
            @endforeach

            @if($doctors->count() > 1)
                <div class="dr-spotlight__switcher" role="tablist" aria-label="Choose a doctor to view">
                    @foreach($doctors as $doctor)
                        <button type="button" class="dr-spotlight__tab @if($loop->first) is-active @endif"
                                id="dr-tab-{{ $doctor->id }}" role="tab"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="dr-panel-{{ $doctor->id }}"
                                data-doctor-tab="{{ $doctor->id }}">
                            <span class="dr-spotlight__tab-photo">
                                @if($doctor->photo)
                                    <img src="{{ image_url($doctor->photo) }}" alt="" loading="lazy">
                                @else
                                    <span class="avatar-initials">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                                @endif
                            </span>
                            <span class="dr-spotlight__tab-text">
                                <strong>{{ $doctor->name }}</strong>
                                @if($doctor->role)<small>{{ $doctor->role }}</small>@endif
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="center">
            <a href="{{ route('doctors') }}" class="btn btn--outline">Read all doctor bios <x-icon name="arrow-right" class="w-4 h-4"/></a>
        </div>
    </div>
</section>
@endif

@foreach($panels as $panel)
    <section class="media-panel @if($loop->odd) media-panel--rev @endif">
        <div class="media-panel__img" data-reveal>
            <img src="{{ image_url($panel->image) }}" alt="@if($panel->title){{ $panel->title }}@endif" loading="lazy">
        </div>
        <div class="media-panel__body" data-reveal>
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

@if($testimonials->isNotEmpty())
<section class="section testimonials" aria-label="Patient feedback">
    <div class="container narrow">
        <div class="section-head section-head--center" data-reveal>
            <h2>What our patients say</h2>
        </div>
        <div data-reveal data-react-root="testimonials" data-testimonials="{{ $testimonials->map(fn ($t) => [
            'content' => $t->content,
            'name' => $t->name,
            'context' => $t->context,
        ])->toJson() }}"></div>
    </div>
</section>
@endif

<section class="booking-strip" id="book">
    <div class="container booking-strip__inner" data-reveal>
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
