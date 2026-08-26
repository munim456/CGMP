@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our doctors</h1>
        <p class="page-hero__sub">Experienced GPs who take the time to listen. Search or browse by last name to find the right doctor for you.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        @if($doctors->isNotEmpty())
            <div class="doctor-directory" data-doctor-directory>
                <div class="doctor-directory__toolbar">
                    <div class="search-field doctor-directory__search">
                        <x-icon name="search" class="w-4 h-4"/>
                        <input type="search" placeholder="Search by name…" aria-label="Search doctors by name" data-doctor-search>
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
                                <th class="is-sortable" data-sort="name">
                                    Name <x-icon name="chevron-down" class="w-4 h-4 sort-icon"/>
                                </th>
                                <th class="is-sortable" data-sort="interests">
                                    Special interests <x-icon name="chevron-down" class="w-4 h-4 sort-icon"/>
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
                                        <span class="doctor-table__interests">{{ $doctor->special_interests ?: '-' }}</span>
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
