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

<section class="section">
    <div class="container">
        @if($doctors->isNotEmpty())
            <div class="doctor-directory" data-doctor-directory>
                <div class="doctor-directory__toolbar">
                    <div class="search-field doctor-directory__search">
                        <x-icon name="search" class="w-4 h-4"/>
                        <input type="search" placeholder="Search by doctor name or specialty…" aria-label="Search doctors by name or specialty" data-doctor-search>
                    </div>
                    <p class="doctor-directory__label">Filter results by last name</p>
                </div>

                <div class="az-filter" role="group" aria-label="Filter doctors by last name" data-az-filter>
                    <button type="button" class="az-filter__btn is-active" data-az-letter="all">All</button>
                    @foreach(range('A', 'Z') as $letter)
                        <button type="button" class="az-filter__btn" data-az-letter="{{ $letter }}">{{ $letter }}</button>
                    @endforeach
                </div>

                <div class="doctor-table-wrap">
                    <table class="doctor-table" data-doctor-table>
                        <thead>
                            <tr>
                                <th class="is-sortable" data-sort="name" aria-sort="none">
                                    <button type="button" class="sort-btn">Name <x-icon name="chevron-down" class="w-4 h-4 sort-icon"/></button>
                                </th>
                                <th class="is-sortable" data-sort="interests" aria-sort="none">
                                    <button type="button" class="sort-btn">Special interests <x-icon name="chevron-down" class="w-4 h-4 sort-icon"/></button>
                                </th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                                    <span class="avatar-initials">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="doctor-table__link">{{ $doctor->name }}</span>
                                                @if($doctor->role || $doctor->qualifications)
                                                    <p class="doctor-table__quals">{{ collect([$doctor->role, $doctor->qualifications])->filter()->implode(' · ') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Special interests">
                                        @if($doctor->special_interests)
                                            <ul class="doctor-table__interests-list">
                                                @foreach(array_filter(array_map('trim', explode(',', $doctor->special_interests))) as $interest)
                                                    <li>{{ $interest }}</li>
                                                @endforeach
                                            </ul>
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

@include('partials.booking-strip-cta')
@endsection
