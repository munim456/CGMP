<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'clinic_name' => 'Cringila General Medical Practice',
            'tagline' => 'Your family GP in Cringila, NSW',

            'phone' => '(02) XXXX XXXX',
            'fax' => '',
            'contact_email' => 'reception@cgmp.com.au',
            'address_line1' => '[Street address - verify with practice]',
            'address_suburb' => 'Cringila NSW 2502',
            'emergency_note' => 'In a medical emergency, call 000 immediately.',
            'opening_hours' => "Monday - Friday: 8:30am - 5:30pm\nSaturday: Closed\nSunday & public holidays: Closed",
            'opening_hours_schema' => 'Mo-Fr 08:30-17:30',
            'google_map_embed' => 'https://maps.google.com/maps?q=Cringila%20NSW%202502&t=&z=14&ie=UTF8&iwloc=&output=embed',

            'healthengine_url' => 'https://healthengine.com.au/',
            'healthengine_embed' => '',
            'walk_in_note' => 'Walk-in appointments are welcome. Patients with a booked appointment are seen first.',

            'facebook_url' => '',
            'instagram_url' => '',
            'youtube_url' => '',

            'palette' => '',
            'meta_title_template' => ':title | :site',
            'meta_description_default' => "Cringila General Medical Practice: caring GPs open five days a week. Special interests in mental health, men’s and women’s health and chronic disease management. Same-day appointments available and walk-ins welcome.",
            'analytics_code' => '',
            'og_image_path' => null,

            'footer_text' => 'Cringila General Medical Practice provides comprehensive primary care to individuals and families in Cringila and the surrounding Illawarra communities.',
            'copyright_text' => 'Cringila General Medical Practice. All rights reserved.',
            'contact_form_disclaimer' => 'This form is for general enquiries only and is not monitored daily. It must not be used for medical advice or emergencies. Please call <strong>000</strong> in an emergency or the clinic during opening hours.',
        ];

        foreach ($settings as $key => $value) {
            if ($value === null) {
                continue;
            }
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
