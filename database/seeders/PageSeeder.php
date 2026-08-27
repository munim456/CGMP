<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Cringila General Medical Practice',
                'subtitle' => 'Community-focused general practice in the heart of Cringila',
                'body' => '<p>Cringila General Medical Practice is open <strong>five days a week</strong> and provides comprehensive primary care to individuals and families in Cringila and the wider Illawarra region.</p><h2>Our approach</h2><p>Our GPs have special interests in mental health, men’s health, women’s health and chronic disease management. We believe in unhurried, personalised consultations, taking the time to understand what matters to you.</p><h2>Convenient care</h2><ul><li><strong>Same-day appointments</strong> are available for urgent concerns.</li><li><strong>Walk-ins are welcome</strong>, patients with bookings are seen first.</li><li>Easy parking and public transport access.</li></ul><h2>Our facilities</h2><p>The practice offers modern consulting rooms, a treatment room for minor procedures and wound care, on-site pathology collection, and wheelchair access throughout.</p><h2>Our commitment</h2><p>We believe good healthcare starts with being heard. Every consultation is unhurried, every patient is treated as a whole person rather than a list of symptoms, and every member of our team, from reception to our GPs, is here to make your visit as straightforward and comfortable as possible.</p>',
                'meta_title' => 'About the Practice | Cringila General Medical Practice',
                'meta_description' => 'Learn about Cringila General Medical Practice: open five days a week with same-day appointments, walk-ins welcome, and GPs experienced in mental health, men’s and women’s health.',
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'subtitle' => null,
                'body' => '<p><em>[Template - to be reviewed by the practice before launch.]</em></p><h2>1. Introduction</h2><p>Cringila General Medical Practice (“the practice”, “we”) is committed to protecting your privacy and handling your personal information in accordance with the Australian Privacy Principles (APPs) under the Privacy Act 1988 (Cth).</p><h2>2. Information we collect</h2><p>We collect personal information that you provide through this website, such as your name, email address, phone number and message content when you use our contact form. This website does not store medical records or health information; online bookings are handled by HealthEngine under its own privacy policy.</p><h2>3. How we use information</h2><p>Contact form submissions are used solely to respond to your enquiry. They are never used to provide medical advice.</p><h2>4. Sharing</h2><p>We do not sell or share your personal information with third parties except where required by law or where you have consented.</p><h2>5. Contact</h2><p>For any privacy questions, contact reception during opening hours.</p>',
                'meta_title' => 'Privacy Policy | Cringila General Medical Practice',
                'meta_description' => 'How Cringila General Medical Practice collects, uses and protects personal information submitted through this website.',
            ],
            [
                'slug' => 'terms-of-use',
                'title' => 'Terms of Use',
                'subtitle' => null,
                'body' => '<p><em>[Template - to be reviewed by the practice before launch.]</em></p><h2>1. Website content</h2><p>The information on this website is provided for general information only. It is not medical advice and must not be relied upon as a substitute for consultation with a qualified healthcare professional.</p><h2>2. Emergencies</h2><p>This website and contact form must not be used in emergencies. In an emergency call <strong>000</strong>.</p><h2>3. Online bookings</h2><p>Online appointments are managed by HealthEngine. By booking online you also agree to HealthEngine’s terms and privacy policy.</p><h2>4. Intellectual property</h2><p>All content on this website belongs to Cringila General Medical Practice unless otherwise stated.</p><h2>5. Changes</h2><p>We may update these terms from time to time without notice.</p>',
                'meta_title' => 'Terms of Use | Cringila General Medical Practice',
                'meta_description' => 'Terms of use for the Cringila General Medical Practice website.',
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page + ['is_active' => true]
            );
        }
    }
}
