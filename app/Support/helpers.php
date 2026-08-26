<?php

use App\Models\Section;
use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        if (! array_key_exists($key, $cache)) {
            $cache[$key] = Setting::query()->where('key', $key)->value('value');
        }

        return $cache[$key] ?? $default;
    }
}

if (! function_exists('section_data')) {
    function section_data(string $key, array $default = []): array
    {
        return Section::data($key, $default);
    }
}

if (! function_exists('tel_url')) {
    function tel_url(?string $phone): string
    {
        return preg_replace('/[^\d+]/', '', (string) $phone);
    }
}

if (! function_exists('image_url')) {
    function image_url(?string $path, string $fallback = ''): string
    {
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }

        if ($path && trim($path) !== '') {
            return asset('storage/' . ltrim($path, '/'));
        }

        return $fallback !== '' ? asset($fallback) : '';
    }
}
