<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const GROUPS = [
        'identity' => ['clinic_name', 'tagline', 'logo_path', 'favicon_path', 'palette'],
        'contact' => ['phone', 'fax', 'contact_email', 'address_line1', 'address_suburb', 'emergency_note', 'opening_hours', 'google_map_embed'],
        'booking' => ['healthengine_url', 'healthengine_embed', 'walk_in_note'],
        'social' => ['facebook_url', 'instagram_url', 'youtube_url'],
        'seo' => ['meta_title_template', 'meta_description_default', 'og_image_path', 'analytics_code'],
        'footer' => ['footer_text', 'copyright_text', 'contact_form_disclaimer'],
    ];

    public const UPLOAD_KEYS = ['logo_path', 'favicon_path', 'og_image_path'];

    public function edit(): View
    {
        return view('admin.settings', [
            'groups' => self::GROUPS,
            'values' => Setting::query()->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'clinic_name' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'palette' => ['required', 'in:teal,ocean,green'],
            'phone' => ['nullable', 'string', 'max:40'],
            'fax' => ['nullable', 'string', 'max:40'],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'address_line1' => ['nullable', 'string', 'max:200'],
            'address_suburb' => ['nullable', 'string', 'max:120'],
            'emergency_note' => ['nullable', 'string', 'max:300'],
            'opening_hours' => ['nullable', 'string', 'max:1000'],
            'google_map_embed' => ['nullable', 'string', 'max:2000'],
            'healthengine_url' => ['nullable', 'url', 'max:500'],
            'healthengine_embed' => ['nullable', 'string', 'max:8000'],
            'walk_in_note' => ['nullable', 'string', 'max:400'],
            'facebook_url' => ['nullable', 'url', 'max:300'],
            'instagram_url' => ['nullable', 'url', 'max:300'],
            'youtube_url' => ['nullable', 'url', 'max:300'],
            'meta_title_template' => ['nullable', 'string', 'max:150'],
            'meta_description_default' => ['nullable', 'string', 'max:400'],
            'analytics_code' => ['nullable', 'string', 'max:4000'],
            'footer_text' => ['nullable', 'string', 'max:600'],
            'copyright_text' => ['nullable', 'string', 'max:300'],
            'contact_form_disclaimer' => ['nullable', 'string', 'max:600'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg,ico', 'max:1024'],
            'og_image_file' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
        ];

        $validated = $request->validate($rules);

        foreach (self::GROUPS as $keys) {
            foreach ($keys as $key) {
                if ($key === 'logo_path') {
                    continue;
                }
                if (array_key_exists($key, $validated)) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key]]);
                }
            }
        }

        if ($request->hasFile('logo_file')) {
            MediaUploader::delete(setting('logo_path'));
            Setting::updateOrCreate(
                ['key' => 'logo_path'],
                ['value' => MediaUploader::handle($request->file('logo_file'), 'media/branding')['path']]
            );
        }

        if ($request->hasFile('favicon_file')) {
            MediaUploader::delete(setting('favicon_path'));
            $ext = strtolower($request->file('favicon_file')->getClientOriginalExtension());
            $filename = now()->format('YmdHis') . '-' . \Illuminate\Support\Str::random(6) . '.' . $ext;
            $path = $request->file('favicon_file')->storeAs('media/branding', $filename, 'public');
            Setting::updateOrCreate(['key' => 'favicon_path'], ['value' => $path]);
        }

        if ($request->hasFile('og_image_file')) {
            MediaUploader::delete(setting('og_image_path'));
            Setting::updateOrCreate(
                ['key' => 'og_image_path'],
                ['value' => MediaUploader::handle($request->file('og_image_file'), 'media/branding')['path']]
            );
        }

        return redirect()->route('admin.settings.edit')->with('status', __('Settings saved.'));
    }
}
