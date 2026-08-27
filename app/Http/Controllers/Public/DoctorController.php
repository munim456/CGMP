<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class DoctorController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.doctors', [
            'doctors' => Doctor::query()->active()->get(),
            'featuredTestimonial' => Testimonial::query()->active()->inRandomOrder()->first(),
        ]);
    }
}
