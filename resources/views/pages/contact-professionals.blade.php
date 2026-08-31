@extends('layouts.public')

@section('content')
<section class="section">
    <div class="container">

        <div class="inner-layout">

            <nav class="side-menu" aria-label="Contact section">
                <a href="{{ route('contact') }}" class="side-menu__link">Contact us</a>
                <a href="{{ route('contact.professionals') }}" class="side-menu__link is-header is-active">Information for healthcare professionals</a>
            </nav>

            <div class="inner-main ipn-contact">

                <div class="textboxsideimage" data-reveal>
                    <div class="textboxsideimage-content">
                        <h1>Information for healthcare professionals</h1>
                        <p>We prefer to receive reports electronically.</p>
                        <p style="margin-top:1rem">Please contact us for our HealthLink ID. If you communicate via any other
                            platform, please call
                            <a class="text-link" href="tel:{{ tel_url(setting('phone')) }}">{{ setting('phone') }}</a>
                            to discuss.</p>
                        <p style="margin-top:1rem">Please do not send patient health information via the website contact form.</p>
                    </div>
                    <div class="textboxsideimage-imagecol">
                        <img src="{{ image_url('media/placeholders/contact-side.jpg') }}"
                             alt="Reception team member assisting a patient at {{ setting('clinic_name') }}" width="640" height="427"
                             loading="lazy">
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
