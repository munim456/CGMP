@extends('layouts.public')

@section('content')
<section class="section section--tint section--services-intro">
    <div class="container" data-reveal>
        <div class="intro-card">
            <h1>Fees &amp; Information</h1>
            <p>{{ setting('clinic_name') }} provides a mix of bulk-billed and private consultations. While many visits are bulk-billed, some may include a small out-of-pocket fee to help us maintain quality care.</p>
            <ul class="fee-policy-list">
                <li>Consultation fees vary based on length and complexity as determined by your GP. Reception will inform you of exact charges at the time of booking.</li>
                <li><strong>Payment terms:</strong> required on the day of consultation. We accept cash, EFTPOS, Visa and Mastercard (Diners and AMEX are not accepted).</li>
                <li><strong>Bulk billing:</strong> applied at GP discretion upon presenting a valid Medicare and concession card on arrival.</li>
                <li><strong>Workers compensation:</strong> a claim number must be provided at every visit.</li>
            </ul>
        </div>
    </div>
</section>

<section class="section section--tint section--services-listing section--fees-table">
    <div class="container">
        <div class="services-listing-card">
            <h2>Consultation fees</h2>
            <div class="fee-table-wrap">
                <table class="fee-table">
                    <thead>
                        <tr>
                            <th>Consultation</th>
                            <th>Private fee</th>
                            <th>Medicare rebate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fee-table__consult">Short Appointment</td>
                            <td class="fee-table__amount">$40.05</td>
                            <td class="fee-table__amount">$20.55</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Standard Appointment</td>
                            <td class="fee-table__amount">From $73.90</td>
                            <td class="fee-table__amount">$45.05</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Long Appointment</td>
                            <td class="fee-table__amount">From $124.90</td>
                            <td class="fee-table__amount">$87.10</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Extended Appointment</td>
                            <td class="fee-table__amount">From $175.10</td>
                            <td class="fee-table__amount">$128.35</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Telehealth Appointment</td>
                            <td class="fee-table__amount">From $73.90</td>
                            <td class="fee-table__amount">$45.05</td>
                        </tr>

                        <tr class="fee-table__group-row">
                            <th scope="colgroup">After Hours Consultations</th>
                            <th>Fee</th>
                            <th>Medicare Rebate</th>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Standard Appointment</td>
                            <td class="fee-table__amount">$87.15</td>
                            <td class="fee-table__amount">$58.65</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Long Appointment</td>
                            <td class="fee-table__amount">$138.70</td>
                            <td class="fee-table__amount">$100.55</td>
                        </tr>
                        <tr>
                            <td class="fee-table__consult">Extended Appointment</td>
                            <td class="fee-table__amount">$187.40</td>
                            <td class="fee-table__amount">$140.95</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="fee-notices">
                <p><strong>Important fee notices:</strong></p>
                <ul>
                    <li>From <strong>1 March 2026</strong>, a <strong>$25 gap fee</strong> applies for international BUPA and NIB patients without a Medicare card.</li>
                    <li>A deposit of <strong>$90-$100</strong> may be requested to secure bookings for new patients, or patients who have not visited the practice in the last two years.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section section--tint section--services-listing">
    <div class="container">
        <div class="services-listing-card">
            <h2>Information</h2>
            <div class="info-accordion">
                <details class="info-accordion__item">
                    <summary>After hours <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Details on after-hours care coverage and emergency contact procedures.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Appointments <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Information regarding online booking options, walk-ins, and standard appointment lengths.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Calling your GP <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Guidelines on phone inquiries, message delivery, and doctor callback schedules.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Cancellations and Did Not Attend policy <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Overview of cancellation notice timelines and non-attendance fees.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Electronic communication <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Policies regarding email communications, privacy, and response times.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Emergencies <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>For life-threatening emergencies, please call 000 immediately.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Home visits <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Eligibility requirements and booking procedures for home visit requests.</p>
                    </div>
                </details>
                <details class="info-accordion__item">
                    <summary>Medical certificates <x-icon name="chevron-down" class="w-5 h-5 info-accordion__chevron"/></summary>
                    <div class="info-accordion__body">
                        <p>Conditions and consultation requirements for obtaining medical certificates.</p>
                    </div>
                </details>
            </div>
        </div>
    </div>
</section>

<button type="button" class="back-to-top" data-back-to-top aria-label="Back to top" hidden>
    <x-icon name="chevron-down" class="w-6 h-6 back-to-top__icon"/>
</button>

@include('partials.booking-strip-cta')
@endsection
