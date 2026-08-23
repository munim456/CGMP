<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class BookingController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.booking');
    }
}
