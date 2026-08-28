<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RedirectController extends Controller
{
    public function index(): View
    {
        return view('admin.redirects.index', [
            'redirects' => Redirect::query()->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Redirect::create($validated);

        return back()->with('status', __('Redirect saved.'));
    }

    public function update(Request $request, Redirect $redirect): RedirectResponse
    {
        $validated = $this->validated($request, $redirect);

        $redirect->update($validated);

        return back()->with('status', __('Redirect updated.'));
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return back()->with('status', __('Redirect deleted.'));
    }

    private function validated(Request $request, ?Redirect $redirect = null): array
    {
        $request->merge(['source' => Redirect::normalize((string) $request->input('source'))]);

        $validated = $request->validate([
            'source' => [
                'required', 'string', 'max:2048',
                Rule::unique('redirects', 'source')->ignore($redirect?->id),
            ],
            'destination' => ['required', 'string', 'max:2048'],
            'status_code' => ['required', 'integer', 'in:301,302'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
