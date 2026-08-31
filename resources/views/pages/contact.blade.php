@extends('layouts.public')

@section('content')
<section class="section">
    <div class="container">

        <div class="inner-layout">

            <nav class="side-menu" aria-label="Contact section">
                <a href="{{ route('contact') }}" class="side-menu__link is-header is-active">Contact us</a>
                <a href="{{ route('contact.professionals') }}" class="side-menu__link">Information for healthcare professionals</a>
            </nav>

            <div class="inner-main ipn-contact">

                <div class="textboxsideimage" data-reveal>
                    <div class="textboxsideimage-content">
                        <h1>Contact us</h1>
                        <p>Please phone {{ setting('clinic_name') }} for information regarding appointments, test results or doctor
                            communication. To protect your privacy, we ask that patients avoid sending personal health details by email.</p>
                        <p style="margin-top:1rem">We continually strive to improve our services to you. If you have a comment or
                            complaint, please:</p>
                        <ul class="tick-list">
                            <li><x-icon name="phone" class="w-4 h-4"/> <span>Speak with our practice manager on
                                <a class="text-link" href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a></span></li>
                            <li><x-icon name="message-square" class="w-4 h-4"/> <span>Give your comments via the online form below</span></li>
                        </ul>
                        <p style="margin-top:1rem">We will endeavour to respond to your feedback within 2 working days.</p>
                    </div>
                    <div class="textboxsideimage-imagecol">
                        <img src="{{ image_url('media/placeholders/contact-side.jpg') }}"
                             alt="Reception team member assisting a patient at {{ setting('clinic_name') }}" width="640" height="427"
                             loading="lazy">
                    </div>
                </div>

                <div class="contact-emergency" role="note" data-reveal>
                    <x-icon name="alert-triangle" class="w-5 h-5 icon"/>
                    <span><strong>In a medical emergency, call 000 immediately.</strong> This form must not be used for urgent medical issues.</span>
                </div>

                <div class="contact-form-wrap" id="feedback-form" data-reveal>
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
                            <label for="cf-first-name">First name <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="cf-first-name" name="first_name" value="{{ old('first_name') }}" required maxlength="60"
                                   aria-describedby="@error('first_name') cf-first-name-error @enderror">
                            @error('first_name')<p class="field-error" id="cf-first-name-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="cf-surname">Surname <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="cf-surname" name="surname" value="{{ old('surname') }}" required maxlength="60"
                                   aria-describedby="@error('surname') cf-surname-error @enderror">
                            @error('surname')<p class="field-error" id="cf-surname-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label for="cf-phone">Phone <span class="req" aria-hidden="true">*</span></label>
                            <input type="tel" id="cf-phone" name="phone" value="{{ old('phone') }}" required maxlength="30"
                                   aria-describedby="@error('phone') cf-phone-error @enderror">
                            @error('phone')<p class="field-error" id="cf-phone-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="cf-email">Email <span class="req" aria-hidden="true">*</span></label>
                            <input type="email" id="cf-email" name="email" value="{{ old('email') }}" required maxlength="180"
                                   aria-describedby="@error('email') cf-email-error @enderror">
                            @error('email')<p class="field-error" id="cf-email-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="cf-message">Feedback <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="cf-message" name="message" rows="6" required maxlength="3000"
                                  aria-describedby="@error('message') cf-message-error @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="field-error" id="cf-message-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn--accent btn--lg">
                        <x-icon name="mail" class="w-5 h-5"/> Submit</button>

                    <p class="form-disclaimer"><x-icon name="info" class="w-4 h-4"/> {!! setting('contact_form_disclaimer') !!}</p>
                </form>
            </div>

            <div class="acknowledgement" data-reveal>
                <h2>Indigenous Acknowledgement</h2>
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
