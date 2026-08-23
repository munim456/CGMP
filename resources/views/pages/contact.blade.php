@extends('layouts.public')

@section('content')
<section class="section">
    <div class="container inner-layout">

        <aside class="inner-side">
            <nav class="side-menu" aria-label="Section menu">
                <span class="side-menu__link is-header is-active" aria-current="page">Contact Us</span>
            </nav>
        </aside>

        <div class="inner-main">

            <div class="textboxsideimage">
                <div data-reveal>
                    <h1><strong>Contact us</strong></h1>
                    <p>Thank you for visiting the website of {{ setting('clinic_name') }}.
                        Please feel free to contact us with any enquiries you may have about our practice —
                        we would love to hear from you.</p>
                    <ul class="text-list">
                        <li>For all general enquiries, please call
                            <a href="tel:{{ preg_replace('/\s+/', '', setting('phone', '')) }}" class="text-link">{{ setting('phone') }}</a>
                            during opening hours.</li>
                        <li>You can find us at {{ setting('address_line1') }}, {{ setting('address_suburb') }}.</li>
                        @if(setting('fax'))<li>Fax: {{ setting('fax') }}</li>@endif
                        <li>To book an appointment, use the <strong>Book online</strong> button at the top of this page.</li>
                    </ul>
                </div>
                <div class="textboxsideimage-imagecol" data-reveal>
                    <img src="{{ image_url('media/placeholders/contact-side.jpg') }}"
                         alt="Reception team member assisting a patient at {{ setting('clinic_name') }}" width="640" height="427"
                         loading="lazy">
                </div>
            </div>

            <div class="contact-emergency" role="note">
                <x-icon name="alert-triangle" class="w-5 h-5 icon"/>
                <span><strong>In a medical emergency, call 000 immediately.</strong> This form must not be used for urgent medical issues.</span>
            </div>

            <div class="contact-form-wrap" id="feedback-form">
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

            <div class="acknowledgement">
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
