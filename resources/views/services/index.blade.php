@extends('layouts.public')

@php
    $serviceDirectory = [
        ['title' => 'Antenatal Care', 'icon' => 'baby', 'body' => 'Comprehensive care throughout pregnancy including routine check-ups, fetal growth monitoring, mother wellness tracking, and shared-care arrangements with local maternity hospitals.'],
        ['title' => 'Child Health and Immunisation', 'icon' => 'shield-check', 'body' => 'Infant health checks, growth and development milestones assessment, National Immunisation Program (NIP) childhood vaccinations, and adolescent health advice.'],
        ['title' => 'Chronic Disease Management', 'icon' => 'activity', 'body' => 'Customised GP Management Plans (GPMP) and Team Care Arrangements (TCA) for diabetes, asthma, hypertension, heart disease, and osteoarthritis management.'],
        ['title' => 'Driving, Diving and Licence Assessments', 'icon' => 'file-text', 'body' => 'Commercial and private driver licence medical assessments, recreational diving medicals, and pre-employment medical examinations.'],
        ['title' => 'DVA and Veterans Affairs', 'icon' => 'star', 'body' => 'Dedicated healthcare services and direct billing care plans for Department of Veterans\' Affairs (DVA) cardholders and military personnel.'],
        ['title' => 'Health Assessments and Preventative Care', 'icon' => 'heart-pulse', 'body' => 'Annual health checks, 45-49 year old health assessments, 75+ annual health assessments, cardiovascular risk screening, and lifestyle advice.'],
        ['title' => 'Indigenous Health', 'icon' => 'flower', 'body' => 'Culturally safe primary healthcare, Closing the Gap (CTG) PBS co-payment program support, and tailored Aboriginal and Torres Strait Islander health assessments (Item 715).'],
        ['title' => "Men's Health", 'icon' => 'dumbbell', 'body' => 'Comprehensive prostate screenings, cardiovascular checks, mental health support, hormonal assessment, and general wellness checks tailored for men of all ages.'],
        ['title' => 'Mental Health', 'icon' => 'brain', 'body' => 'GP Mental Health Care Plans (Mental Health Treatment Plans), psychological assessment, counselling referrals, and ongoing support for anxiety, depression, and stress management.'],
        ['title' => 'Minor Procedures and Aftercare', 'icon' => 'stethoscope', 'body' => 'On-site minor surgical procedures including skin lesion removal, biopsy, wound suturing, cryotherapy, ingrown toenail procedures, and post-op wound care.'],
        ['title' => 'Pain Management', 'icon' => 'alert-triangle', 'body' => 'Evaluation and multidisciplinary care planning for chronic pain conditions, medication reviews, and referrals to specialised pain clinics.'],
        ['title' => "Senior's Health", 'icon' => 'user-round', 'body' => 'Dedicated geriatric care including memory assessments, mobility reviews, medication management, home safety advice, and aged care planning.'],
        ['title' => 'Sexual Health', 'icon' => 'users', 'body' => 'Confidential STI testing and treatment, contraception advice, family planning, cervical screening tests (CST), and sexual wellness consultations.'],
        ['title' => 'Skin Cancer Services', 'icon' => 'eye', 'body' => 'Full-body skin cancer checks, dermoscopy assessment, mole scanning, sun spot treatment, and surgical excision of skin cancers.'],
        ['title' => 'Sports Medicine', 'icon' => 'activity', 'body' => 'Diagnosis and management of acute sports injuries, joint and muscle pain evaluation, rehabilitation planning, and physical recovery support.'],
        ['title' => 'Travel Medicine and Travel Vaccinations', 'icon' => 'map-pin', 'body' => 'Pre-travel consultations, destination-specific immunisations, yellow fever accreditation, malaria prophylaxis, and travel safety advice.'],
        ['title' => "Women's Health", 'icon' => 'heart-pulse', 'body' => "Comprehensive women's healthcare including pap smears/cervical screening, breast checks, menopause management, Implanon/Mirena insertion and removal support, and antenatal shared care."],
    ];
@endphp

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our services</h1>
        <p class="page-hero__sub">We cater to the health needs of your whole family. From pregnancy to retirement, here are the {{ count($serviceDirectory) }} primary medical services provided at {{ setting('clinic_name') }}.</p>
        <div class="stats-row stats-row--compact" data-reveal>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="{{ count($serviceDirectory) }}">0</span></span>
                <span class="stat__label">Services offered</span>
            </div>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="5">0</span></span>
                <span class="stat__label">Days open each week</span>
            </div>
        </div>
    </div>
</section>

<section class="section section--tint section--services-listing services-category services-category--primary">
    <div class="container">
        <div class="services-listing-card">
            <h2>Medical services directory</h2>
            <div class="services-accordion">
                @foreach($serviceDirectory as $item)
                    <details class="services-accordion__item">
                        <summary>
                            <span class="services-accordion__icon"><x-icon name="{{ $item['icon'] }}" class="w-5 h-5"/></span>
                            <span class="services-accordion__title">{{ $item['title'] }}</span>
                            <x-icon name="chevron-down" class="w-5 h-5 services-accordion__chevron"/>
                        </summary>
                        <div class="services-accordion__body">
                            <p>{{ $item['body'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

@include('partials.booking-strip-cta')
@endsection
