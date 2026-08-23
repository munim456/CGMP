<section class="booking-strip">
    <div class="container booking-strip__inner" data-reveal>
        <div>
            <h2>{{ section_data('booking_strip')['heading'] ?? 'Ready to see a doctor?' }}</h2>
            <p>{{ section_data('booking_strip')['text'] ?? '' }}</p>
        </div>
        <a href="{{ route('booking') }}" class="btn btn--light btn--lg">
            <x-icon name="calendar-check" class="w-5 h-5"/> {{ section_data('booking_strip')['button_text'] ?? 'Book online with HealthEngine' }}
        </a>
    </div>
</section>
