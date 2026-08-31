@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our doctors</h1>
        <p class="page-hero__sub">Our doctors are experienced in every aspect of family health and can help you at any stage of life, from newborn checks to aged care. Many of our GPs hold extra qualifications and areas of special interest, so you can find the right fit for your family and stay with the same doctor as your needs change over time.</p>
        <div class="chip-row" style="margin-top:1.1rem">
            <span class="chip chip--soft"><x-icon name="calendar-check" class="w-4 h-4"/> Same-day appointments</span>
            <span class="chip chip--soft"><x-icon name="users" class="w-4 h-4"/> Walk-ins welcome</span>
            <span class="chip chip--soft"><x-icon name="heart-pulse" class="w-4 h-4"/> Bulk billing available</span>
        </div>
    </div>
</section>

<section class="section section--doctors-listing services-category services-category--primary">
    <div class="container">
        @if($doctors->isNotEmpty())
            <div class="doctor-directory" data-doctor-directory data-reveal>
                <div class="doctor-directory__toolbar">
                    <div class="search-field doctor-directory__search">
                        <x-icon name="search" class="w-4 h-4"/>
                        <input type="search" placeholder="Search by doctor name or specialty…" aria-label="Search doctors by name or specialty" data-doctor-search>
                    </div>
                </div>

                @php
                    $doctorLastNames = $doctors->map(fn ($doctor) => mb_strtoupper(mb_substr(collect(explode(' ', preg_replace('/^Dr\.?\s+/i', '', $doctor->name)))->last(), 0, 1)));
                @endphp
                <p class="doctor-directory__az-label" id="doctor-az-label">Filter results by last name</p>
                <div class="az-filter" role="group" aria-labelledby="doctor-az-label" data-az-filter>
                    <button type="button" class="az-filter__btn is-active" data-az-letter="all">All</button>
                    @foreach(range('A', 'Z') as $letter)
                        <button type="button"
                            class="az-filter__btn{{ $doctorLastNames->contains($letter) ? '' : ' is-disabled' }}"
                            data-az-letter="{{ strtolower($letter) }}"
                            @if(!$doctorLastNames->contains($letter)) disabled @endif>{{ $letter }}</button>
                    @endforeach
                </div>

                <div class="doctor-table-wrap">
                    <table class="doctor-table" data-doctor-table>
                        <thead>
                            <tr>
                                <th class="is-sortable is-sorted-asc">
                                    <button type="button" class="sort-btn" data-sort-key="name">Name <x-icon name="chevron-down" class="w-3 h-3 sort-icon"/></button>
                                </th>
                                <th class="is-sortable">
                                    <button type="button" class="sort-btn" data-sort-key="interests">Special interests <x-icon name="chevron-down" class="w-3 h-3 sort-icon"/></button>
                                </th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody data-doctor-list>
                            @foreach($doctors as $doctor)
                                @php
                                    $lastName = collect(explode(' ', preg_replace('/^Dr\.?\s+/i', '', $doctor->name)))->last();
                                @endphp
                                <tr data-doctor-row
                                    data-name="{{ strtolower($doctor->name) }}"
                                    data-last-name="{{ strtolower($lastName) }}"
                                    data-interests="{{ strtolower($doctor->special_interests ?? '') }}">
                                    <td data-label="Name">
                                        <div class="doctor-table__name">
                                            <div class="doctor-table__photo">
                                                @if($doctor->photo)
                                                    <img src="{{ image_url($doctor->photo) }}" alt="Photo of {{ $doctor->name }}" loading="lazy">
                                                @else
                                                    <span class="doctor-table__photo-placeholder"><x-icon name="stethoscope" class="w-6 h-6"/></span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="doctor-table__link">{{ $doctor->name }}</span>
                                                @if($doctor->role || $doctor->qualifications)
                                                    <p class="doctor-table__quals">{{ collect([$doctor->role, $doctor->qualifications])->filter()->implode(' · ') }}</p>
                                                @endif
                                                @if($doctor->bio)
                                                    <p class="doctor-table__bio">{{ \Illuminate\Support\Str::limit(strip_tags($doctor->bio), 110) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Special interests">
                                        @if($doctor->special_interests)
                                            <div class="doctor-table__interests-tags">
                                                @foreach(array_filter(array_map('trim', explode(',', $doctor->special_interests))) as $interest)
                                                    <span class="chip chip--soft">{{ $interest }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="doctor-table__interests">-</span>
                                        @endif
                                    </td>
                                    <td data-label="Bookings">
                                        <a href="{{ route('booking') }}" class="btn btn--outline btn--sm">Book now</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="doctor-table__empty" data-doctor-empty hidden>No doctors match your search.</p>
            </div>
        @else
            <p class="muted center" data-reveal>Doctor profiles will be published shortly.</p>
        @endif
    </div>
</section>

<section class="section section--tint services-category services-category--accent">
    <div class="container trust-panel">
        <div data-reveal>
            <div class="section-head">
                <h2>Why patients choose our doctors</h2>
                <p class="section-head__lead">Whoever you see, you can expect the same standard of care.</p>
            </div>
            <ul class="trust-list">
                <li>
                    <span class="trust-list__icon"><x-icon name="calendar-check" class="w-5 h-5"/></span>
                    <div>
                        <h3>Same-day appointments</h3>
                        <p>Urgent concerns are seen the same day wherever possible, alongside your regular bookings.</p>
                    </div>
                </li>
                <li>
                    <span class="trust-list__icon"><x-icon name="users" class="w-5 h-5"/></span>
                    <div>
                        <h3>Walk-ins welcome</h3>
                        <p>No regular GP or booking? Come in and our reception team will fit you in around scheduled patients.</p>
                    </div>
                </li>
                <li>
                    <span class="trust-list__icon"><x-icon name="heart-pulse" class="w-5 h-5"/></span>
                    <div>
                        <h3>Bulk billing available</h3>
                        <p>Ask reception about bulk billing eligibility so cost is never a barrier to seeing a doctor.</p>
                    </div>
                </li>
                <li>
                    <span class="trust-list__icon"><x-icon name="graduation-cap" class="w-5 h-5"/></span>
                    <div>
                        <h3>Extra qualifications</h3>
                        <p>Many of our GPs hold additional training in specific areas, listed against their profile above.</p>
                    </div>
                </li>
            </ul>
        </div>

        @if($featuredTestimonial)
            <div class="trust-quote" data-reveal>
                <x-icon name="quote" class="w-8 h-8 trust-quote__mark"/>
                <blockquote>{{ $featuredTestimonial->content }}</blockquote>
                <cite>{{ $featuredTestimonial->name }}@if($featuredTestimonial->context)<span>{{ $featuredTestimonial->context }}</span>@endif</cite>
            </div>
        @endif
    </div>
</section>
@endsection
