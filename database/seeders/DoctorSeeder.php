<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr Homayera Noor',
                'role' => 'Practice Principal',
                'qualifications' => 'MBBS, DCH, FRACGP',
                'special_interests' => 'Women\'s health, Child health, Chronic disease management',
                'bio' => '<p>Dr Homayera Noor is the Practice Principal at Cringila General Medical Practice and a Fellow of the Royal Australian College of General Practitioners (FRACGP). She holds a Diploma of Child Health (DCH) and has a special interest in women’s health, child health and chronic disease management.</p><p>Dr Noor is committed to providing thorough, compassionate care to families across the Illawarra.</p>',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dr Muhammad Iqbal',
                'role' => 'General Practitioner',
                'qualifications' => 'MBBS, Dip. Occup. Health & Safety (UOW), NSW Medical Acupuncture Course',
                'special_interests' => 'Men\'s health, Occupational medicine, Injury management, Acupuncture',
                'bio' => '<p>Dr Muhammad Iqbal is an experienced GP with a background in occupational health and safety, gained through his diploma from the University of Wollongong. He has completed the NSW Medical Acupuncture Course and offers acupuncture alongside general practice care.</p><p>His interests include men’s health, occupational medicine, injury management and chronic disease.</p>',
                'sort_order' => 2,
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::query()->updateOrCreate(
                ['name' => $doctor['name']],
                $doctor + ['is_active' => true]
            );
        }
    }
}
