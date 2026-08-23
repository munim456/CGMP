<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Contracts\View\View;

class DoctorController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.doctors', [
            'doctors' => Doctor::query()->active()->get(),
        ]);
    }
}
