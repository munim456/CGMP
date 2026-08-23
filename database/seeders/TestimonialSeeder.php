<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Margaret T.',
                'context' => 'Patient for 12 years',
                'content' => 'The doctors here genuinely listen. Nothing is ever rushed and I always leave feeling looked after.',
            ],
            [
                'name' => 'Daniel K.',
                'context' => 'Walk-in patient',
                'content' => 'Walked in with my son on a busy Monday and was still seen promptly. Friendly staff from reception to the doctor.',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->firstOrCreate(
                ['name' => $testimonial['name']],
                $testimonial + ['rating' => 5, 'is_active' => true]
            );
        }
    }
}
