<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['key', 'content'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public static function data(string $key, array $default = []): array
    {
        $section = static::query()->where('key', $key)->first();

        return $section?->content ?? $default;
    }

    public static function store(string $key, array $content): void
    {
        static::updateOrCreate(['key' => $key], ['content' => $content]);
    }
}
