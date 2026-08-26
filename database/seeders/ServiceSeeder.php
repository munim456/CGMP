<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Mental Health Care',
                'icon' => 'brain',
                'short_description' => 'Supportive GP mental health care, mental health care plans and referrals to allied psychological services.',
                'description' => '<p>Our GPs provide confidential support for anxiety, depression, stress and other mental health concerns. We can prepare <strong>Mental Health Care Plans</strong> that provide Medicare-subsidised access to psychology services, and we work alongside psychologists and psychiatrists to coordinate your ongoing care.</p><p>If you are in crisis, call <strong>Lifeline on 13 11 14</strong> or <strong>000</strong> in an emergency.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Men’s Health',
                'icon' => 'user-round',
                'short_description' => 'Health checks, preventive screening and management of conditions affecting men at every age.',
                'description' => '<p>From heart health checks and blood pressure monitoring to prostate health, diabetes screening and lifestyle advice, our GPs offer judgement-free care tailored to men’s health needs. Regular check-ups help catch problems early. Book a men’s health assessment today.</p>',
                'sort_order' => 2,
            ],
            [
                'title' => "Women’s Health",
                'icon' => 'flower',
                'short_description' => 'Cervical screening, contraception, antenatal shared care, menopause support and more.',
                'description' => '<p>We provide a full range of women’s health services, including cervical screening tests, contraceptive advice, antenatal shared care with the hospital, breast health checks, and support through perimenopause and menopause.</p>',
                'sort_order' => 3,
            ],
            [
                'title' => 'Chronic Disease Management',
                'icon' => 'heart-pulse',
                'short_description' => 'GP Management Plans and Team Care Arrangements for diabetes, asthma, heart disease and other ongoing conditions.',
                'description' => '<p>Living well with a chronic condition starts with a plan. Our GPs prepare <strong>Chronic Disease Management Plans (GPMP)</strong> and <strong>Team Care Arrangements</strong>, coordinating nurses, physiotherapists, dietitians and specialists so you get joined-up care and access to Medicare-subsidised allied health visits.</p>',
                'sort_order' => 4,
            ],
            [
                'title' => 'Diabetes Care',
                'icon' => 'activity',
                'short_description' => 'Diagnosis, monitoring, medication reviews and lifestyle support for type 1 and type 2 diabetes.',
                'description' => '<p>Diabetes is one of the fastest-growing chronic conditions in Australia. Our practice provides comprehensive diabetes care: early diagnosis, regular HbA1c monitoring, medication and insulin management, annual cycle-of-care checks, and referrals to diabetes educators, podiatrists and dietitians.</p>',
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($service['title'])],
                $service + ['is_active' => true]
            );
        }
    }
}
