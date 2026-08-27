@extends('layouts.public')

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Contact us</h1>
        <p class="page-hero__sub">Thank you for visiting the website of {{ setting('clinic_name') }}. Whether you have a question about
            an appointment, a billing enquiry, or general feedback about your visit, our reception team is happy to help.
            Call us during opening hours, drop us a message below, or find us at the address and map further down this page.</p>
        <div class="chip-row" style="margin-top:1.1rem">
            <span class="chip chip--soft"><x-icon name="phone" class="w-4 h-4"/> {{ setting('phone') }}</span>
            <span class="chip chip--soft"><x-icon name="clock" class="w-4 h-4"/> Open 5 days a week</span>
            <span class="chip chip--soft"><x-icon name="map-pin" class="w-4 h-4"/> {{ setting('address_suburb') }}</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="inner-main">

            <div class="textboxsideimage-imagecol" data-reveal>
                <div class="contact-photo-frame">
                    <img src="{{ image_url('media/placeholders/contact-side.jpg') }}"
                         alt="Reception team member assisting a patient at {{ setting('clinic_name') }}" width="640" height="427"
                         loading="lazy">
                </div>
            </div>

            <div class="contact-methods">
                <div class="contact-method-card" data-reveal>
                    <span class="contact-method-card__icon"><x-icon name="phone" class="w-5 h-5"/></span>
                    <h3>Call us</h3>
                    <p>For all general enquiries during opening hours.</p>
                    <a href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a>
                </div>
                <div class="contact-method-card" data-reveal>
                    <span class="contact-method-card__icon"><x-icon name="map-pin" class="w-5 h-5"/></span>
                    <h3>Visit us</h3>
                    <p>{{ setting('address_line1') }}<br>{{ setting('address_suburb') }}</p>
                    @if(setting('fax'))<p>Fax: {{ setting('fax') }}</p>@endif
                </div>
                <div class="contact-method-card" data-reveal>
                    <span class="contact-method-card__icon"><x-icon name="calendar-check" class="w-5 h-5"/></span>
                    <h3>Book online</h3>
                    <p>Use the Book online button at the top of this page.</p>
                    <a href="{{ route('booking') }}">Book an appointment</a>
                </div>
            </div>

            <div class="quick-facts-card" data-reveal>
                <span class="quick-facts-card__icon"><x-icon name="clock" class="w-6 h-6"/></span>
                <div>
                    <h2>Opening hours</h2>
                    <div class="quick-facts-card__hours">
                        @foreach(preg_split('/\r\n|\r|\n/', trim(setting('opening_hours'))) as $line)
                            @if(trim($line) !== '')<span>{{ trim($line) }}</span>@endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="contact-emergency" role="note" data-reveal>
                <x-icon name="alert-triangle" class="w-5 h-5 icon"/>
                <span><strong>In a medical emergency, call 000 immediately.</strong> This form must not be used for urgent medical issues.</span>
            </div>

            <div class="contact-form-wrap" id="feedback-form" data-reveal>
                <h2>Feedback Form</h2>

                @if(session('status'))
                    <div class="alert alert--success" role="status">
                        <x-icon name="check-circle" class="w-5 h-5"/> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="contact-form" novalidate>
                    @csrf
                    <input type="text" name="website" value="" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="form-row">
                        <div class="field">
                            <label for="cf-name">Your name <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="cf-name" name="name" value="{{ old('name') }}" required maxlength="120"
                                   aria-describedby="@error('name') cf-name-error @enderror">
                            @error('name')<p class="field-error" id="cf-name-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="cf-phone">Phone</label>
                            <input type="tel" id="cf-phone" name="phone" value="{{ old('phone') }}" maxlength="30">
                        </div>
                    </div>

                    <div class="field">
                        <label for="cf-email">Email <span class="req" aria-hidden="true">*</span></label>
                        <input type="email" id="cf-email" name="email" value="{{ old('email') }}" required maxlength="180"
                               aria-describedby="@error('email') cf-email-error @enderror">
                        @error('email')<p class="field-error" id="cf-email-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="cf-message">Message <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="cf-message" name="message" rows="6" required maxlength="3000"
                                  aria-describedby="@error('message') cf-message-error @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="field-error" id="cf-message-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn--accent btn--lg">
                        <x-icon name="mail" class="w-5 h-5"/> Send message</button>

                    <p class="form-disclaimer"><x-icon name="info" class="w-4 h-4"/> {!! setting('contact_form_disclaimer') !!}</p>
                </form>
            </div>

            <div class="acknowledgement" data-reveal>
                <div class="acknowledgement__flags">
                    <img src="{{ image_url('media/placeholders/flag-aboriginal.png') }}" alt="Australian Aboriginal Flag" width="90" height="54" loading="lazy">
                    <img src="{{ image_url('media/placeholders/flag-torres-strait.png') }}" alt="Torres Strait Islander Flag" width="90" height="54" loading="lazy">
                </div>
                <p>{{ setting('clinic_name') }} acknowledges the Traditional Custodians of the lands on which we work and live,
                    and recognises their continuing connection to land, waters and community.
                    We pay our respect to Elders past, present and emerging.</p>
            </div>

        </div>
    </div>
</section>

@if(setting('google_map_embed'))
<section class="section section--flush" aria-label="Location map">
    <iframe class="contact-map" loading="lazy"
        src="{{ setting('google_map_embed') }}"
        title="Map showing {{ setting('clinic_name') }} location"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
@endif
@endsection
