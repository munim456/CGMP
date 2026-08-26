<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        Section::store('hero', [
            'badge_text' => 'Walk-ins welcome',
            'heading' => 'Quality care for you and your family, close to home',
            'subheading' => 'Cringila General Medical Practice is open five days a week, with same-day appointments available and experienced GPs who take the time to listen.',
            'primary_button_text' => 'Book Appointment',
            'primary_button_link' => '/book-appointment',
            'secondary_button_text' => 'Our Services',
            'secondary_button_link' => '/services',
        ]);

        Section::store('highlights', [
            'items' => [
                ['icon' => 'briefcase-medical', 'title' => 'Medical Treatment', 'text' => 'Comprehensive primary care for every stage of life.'],
                ['icon' => 'activity', 'title' => 'Emergency Help', 'text' => 'Urgent concerns seen promptly, walk right in.'],
                ['icon' => 'user-round', 'title' => 'Medical Professionals', 'text' => 'A caring team dedicated to your wellbeing.'],
                ['icon' => 'graduation-cap', 'title' => 'Qualified Doctors', 'text' => 'Fellowship-trained GPs with special interests.'],
            ],
        ]);

        $apostrophe = json_decode('"\u2019"');

        Section::store('about', [
            'heading' => 'Caring for the Illawarra community',
            'body' => "<p>Cringila General Medical Practice is open <strong>five days a week</strong>, offering comprehensive healthcare for individuals and families in Cringila and surrounding suburbs.</p><p>Our GPs have special interests in mental health, men{$apostrophe}s health, women{$apostrophe}s health and chronic disease management. Same-day appointments are available and walk-ins are always welcome.</p>",
            'points' => [
                'Open five days a week',
                'Same-day appointments available',
                'Walk-ins welcome',
                "Mental health, men{$apostrophe}s & women{$apostrophe}s health",
                'Chronic disease management',
            ],
            'stats' => [
                ['value' => 20, 'suffix' => '+', 'label' => 'Years caring for the community'],
                ['value' => 2, 'suffix' => '', 'label' => 'Experienced GPs'],
                ['value' => 5000, 'suffix' => '+', 'label' => 'Patients cared for'],
                ['value' => 5, 'suffix' => '', 'label' => 'Days open each week'],
            ],
        ]);

        Section::store('booking_strip', [
            'heading' => 'Ready to see a doctor?',
            'text' => 'Book online in minutes with HealthEngine, call the practice, or simply walk in.',
            'button_text' => 'Book online with HealthEngine',
        ]);
    }
}
