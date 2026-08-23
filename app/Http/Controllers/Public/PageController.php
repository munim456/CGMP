<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function __invoke(?string $static_slug = null, ?Page $page = null): View
    {
        $model = $page
            ?? Page::query()->where('slug', $static_slug)->active()->firstOrFail();

        abort_unless($model->is_active, 404);

        return view('pages.show', [
            'page' => $model,
            'doctors' => $model->slug === 'about' ? Doctor::query()->active()->get() : collect(),
        ]);
    }
}
