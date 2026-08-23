<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public const KEYS = ['hero', 'about', 'highlights', 'booking_strip'];

    public function edit(string $key): View
    {
        abort_unless(in_array($key, self::KEYS, true), 404);

        return view('admin.sections.' . $key, [
            'key' => $key,
            'data' => Section::data($key),
        ]);
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        abort_unless(in_array($key, self::KEYS, true), 404);

        match ($key) {
            'hero' => $this->saveHero($request),
            'about' => $this->saveAbout($request),
            'highlights' => $this->saveHighlights($request),
            'booking_strip' => $this->saveBookingStrip($request),
        };

        return redirect()
            ->route('admin.sections.edit', $key)
            ->with('status', __('Section updated. Changes are live on the website.'));
    }

    protected function saveHero(Request $request): void
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:200'],
            'subheading' => ['nullable', 'string', 'max:400'],
            'badge_text' => ['nullable', 'string', 'max:80'],
            'primary_button_text' => ['nullable', 'string', 'max:60'],
            'primary_button_link' => ['nullable', 'string', 'max:300'],
            'secondary_button_text' => ['nullable', 'string', 'max:60'],
            'secondary_button_link' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:6144'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $data = [
            'heading' => $validated['heading'],
            'subheading' => $validated['subheading'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'primary_button_text' => $validated['primary_button_text'] ?? null,
            'primary_button_link' => $validated['primary_button_link'] ?? null,
            'secondary_button_text' => $validated['secondary_button_text'] ?? null,
            'secondary_button_link' => $validated['secondary_button_link'] ?? null,
        ];

        if ($request->boolean('remove_image')) {
            MediaUploader::delete(Section::data('hero')['image'] ?? null);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            MediaUploader::delete(Section::data('hero')['image'] ?? null);
            $data['image'] = MediaUploader::handle($request->file('image'), 'media/hero')['path'];
        }

        Section::store('hero', array_filter($data, fn ($v) => $v !== null));
    }

    protected function saveAbout(Request $request): void
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:6144'],
            'remove_image' => ['nullable', 'boolean'],
            'points' => ['nullable', 'array', 'max:8'],
            'points.*' => ['nullable', 'string', 'max:120'],
            'stats' => ['nullable', 'array', 'max:4'],
            'stats.*.value' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'stats.*.suffix' => ['nullable', 'string', 'max:5'],
            'stats.*.label' => ['nullable', 'string', 'max:80'],
        ]);

        $current = Section::data('about');

        $data = [
            'heading' => $validated['heading'],
            'body' => $validated['body'],
            'points' => array_values(array_filter($validated['points'] ?? [], fn ($p) => filled($p))),
            'stats' => collect($validated['stats'] ?? [])
                ->filter(fn ($s) => isset($s['label'], $s['value']))
                ->map(fn ($s) => [
                    'value' => (int) $s['value'],
                    'suffix' => $s['suffix'] ?? '',
                    'label' => $s['label'],
                ])->values()->all(),
        ];

        if ($request->boolean('remove_image')) {
            MediaUploader::delete($current['image'] ?? null);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            MediaUploader::delete($current['image'] ?? null);
            $data['image'] = MediaUploader::handle($request->file('image'), 'media/about')['path'];
        } elseif (! empty($current['image'])) {
            $data['image'] = $current['image'];
        }

        Section::store('about', $data);
    }

    protected function saveHighlights(Request $request): void
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:8'],
            'items.*.icon' => ['nullable', 'string', 'max:50'],
            'items.*.title' => ['required', 'string', 'max:100'],
            'items.*.text' => ['nullable', 'string', 'max:300'],
        ]);

        Section::store('highlights', ['items' => $validated['items']]);
    }

    protected function saveBookingStrip(Request $request): void
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:200'],
            'text' => ['nullable', 'string', 'max:300'],
            'button_text' => ['nullable', 'string', 'max:60'],
        ]);

        Section::store('booking_strip', $validated);
    }
}
