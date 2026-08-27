@extends('layouts.public')

@php
    $serviceDirectory = [
        ['title' => 'Antenatal Care', 'icon' => 'baby', 'category' => 'Family health, every stage of life', 'body' => 'Comprehensive care throughout pregnancy including routine check-ups, fetal growth monitoring, mother wellness tracking, and shared-care arrangements with local maternity hospitals.'],
        ['title' => 'Child Health and Immunisation', 'icon' => 'shield-check', 'category' => 'Family health, every stage of life', 'body' => 'Infant health checks, growth and development milestones assessment, National Immunisation Program (NIP) childhood vaccinations, and adolescent health advice.'],
        ['title' => "Women's Health", 'icon' => 'heart-pulse', 'category' => 'Family health, every stage of life', 'body' => "Comprehensive women's healthcare including pap smears/cervical screening, breast checks, menopause management, Implanon/Mirena insertion and removal support, and antenatal shared care."],
        ['title' => "Men's Health", 'icon' => 'dumbbell', 'category' => 'Family health, every stage of life', 'body' => 'Comprehensive prostate screenings, cardiovascular checks, mental health support, hormonal assessment, and general wellness checks tailored for men of all ages.'],
        ['title' => "Senior's Health", 'icon' => 'user-round', 'category' => 'Family health, every stage of life', 'body' => 'Dedicated geriatric care including memory assessments, mobility reviews, medication management, home safety advice, and aged care planning.'],

        ['title' => 'Chronic Disease Management', 'icon' => 'activity', 'category' => 'Ongoing and preventative care', 'body' => 'Customised GP Management Plans (GPMP) and Team Care Arrangements (TCA) for diabetes, asthma, hypertension, heart disease, and osteoarthritis management.'],
        ['title' => 'Mental Health', 'icon' => 'brain', 'category' => 'Ongoing and preventative care', 'body' => 'GP Mental Health Care Plans (Mental Health Treatment Plans), psychological assessment, counselling referrals, and ongoing support for anxiety, depression, and stress management.'],
        ['title' => 'Pain Management', 'icon' => 'alert-triangle', 'category' => 'Ongoing and preventative care', 'body' => 'Evaluation and multidisciplinary care planning for chronic pain conditions, medication reviews, and referrals to specialised pain clinics.'],
        ['title' => 'Health Assessments and Preventative Care', 'icon' => 'heart-pulse', 'category' => 'Ongoing and preventative care', 'body' => 'Annual health checks, 45-49 year old health assessments, 75+ annual health assessments, cardiovascular risk screening, and lifestyle advice.'],

        ['title' => 'Minor Procedures and Aftercare', 'icon' => 'stethoscope', 'category' => 'Procedures and assessments', 'body' => 'On-site minor surgical procedures including skin lesion removal, biopsy, wound suturing, cryotherapy, ingrown toenail procedures, and post-op wound care.'],
        ['title' => 'Skin Cancer Services', 'icon' => 'eye', 'category' => 'Procedures and assessments', 'body' => 'Full-body skin cancer checks, dermoscopy assessment, mole scanning, sun spot treatment, and surgical excision of skin cancers.'],
        ['title' => 'Sports Medicine', 'icon' => 'activity', 'category' => 'Procedures and assessments', 'body' => 'Diagnosis and management of acute sports injuries, joint and muscle pain evaluation, rehabilitation planning, and physical recovery support.'],
        ['title' => 'Driving, Diving and Licence Assessments', 'icon' => 'file-text', 'category' => 'Procedures and assessments', 'body' => 'Commercial and private driver licence medical assessments, recreational diving medicals, and pre-employment medical examinations.'],

        ['title' => 'Indigenous Health', 'icon' => 'flower', 'category' => 'Community and travel health', 'body' => 'Culturally safe primary healthcare, Closing the Gap (CTG) PBS co-payment program support, and tailored Aboriginal and Torres Strait Islander health assessments (Item 715).'],
        ['title' => 'Sexual Health', 'icon' => 'users', 'category' => 'Community and travel health', 'body' => 'Confidential STI testing and treatment, contraception advice, family planning, cervical screening tests (CST), and sexual wellness consultations.'],
        ['title' => 'DVA and Veterans Affairs', 'icon' => 'star', 'category' => 'Community and travel health', 'body' => 'Dedicated healthcare services and direct billing care plans for Department of Veterans\' Affairs (DVA) cardholders and military personnel.'],
        ['title' => 'Travel Medicine and Travel Vaccinations', 'icon' => 'map-pin', 'category' => 'Community and travel health', 'body' => 'Pre-travel consultations, destination-specific immunisations, yellow fever accreditation, malaria prophylaxis, and travel safety advice.'],
    ];
    $grouped = collect($serviceDirectory)->groupBy('category');
@endphp

@section('content')
<section class="page-hero">
    <div class="container" data-reveal>
        <h1>Our services</h1>
        <p class="page-hero__sub">We cater to the health needs of your whole family. From pregnancy to retirement, here are the {{ count($serviceDirectory) }} primary medical services provided at {{ setting('clinic_name') }}, grouped by what matters most to you.</p>
        <div class="stats-row stats-row--compact" data-reveal>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="{{ count($serviceDirectory) }}">0</span></span>
                <span class="stat__label">Services offered</span>
            </div>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="{{ $grouped->count() }}">0</span></span>
                <span class="stat__label">Care categories</span>
            </div>
            <div class="stat">
                <span class="stat__number"><span class="count-up" data-count-to="5">0</span></span>
                <span class="stat__label">Days open each week</span>
            </div>
        </div>
    </div>
</section>

@foreach($grouped as $category => $items)
    <section class="section @if($loop->even) section--tint @endif">
        <div class="container">
            <div class="section-head" data-reveal>
                <h2>{{ $category }}</h2>
                <p class="section-head__lead">{{ $items->count() }} {{ Str::plural('service', $items->count()) }}</p>
            </div>
            <div class="grid grid--3">
                @foreach($items as $item)
                    <article class="service-card" data-reveal>
                        <span class="service-card__icon"><x-icon name="{{ $item['icon'] }}" class="w-6 h-6"/></span>
                        <h2>{{ $item['title'] }}</h2>
                        <p>{{ $item['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endforeach

@include('partials.booking-strip-cta')
@endsection
