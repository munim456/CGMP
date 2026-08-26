<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    public const CACHE_KEY = 'redirects.map';

    protected $fillable = ['source', 'destination', 'status_code', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'status_code' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        foreach (['saved', 'deleted'] as $event) {
            static::$event(fn () => Cache::forget(self::CACHE_KEY));
        }
    }

    public function source(): Attribute
    {
        return Attribute::set(fn ($value) => static::normalize($value));
    }

    /**
     * Canonical form used for both storage and matching: lowercase, no query
     * string or fragment, exactly one leading slash, no trailing slash
     * ("/Contact-Us.html?x=1" and "contact-us.html" collide intentionally).
     */
    public static function normalize(string $path): string
    {
        $path = '/' . ltrim(trim($path), '/');
        $path = strtolower((string) preg_replace('/[?#].*$/', '', $path));

        return rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
    }

    /**
     * @return array{destination: string, status_code: int}|null
     */
    public static function lookup(string $requestPath): ?array
    {
        // Short TTL bounds staleness from bulk SQL edits (which skip model
        // events); Eloquent saves/deletes still flush instantly via booted().
        $map = Cache::remember(
            self::CACHE_KEY,
            now()->addSeconds(60),
            fn () => self::query()
                ->where('is_active', true)
                ->get(['source', 'destination', 'status_code'])
                ->keyBy(fn (self $r) => self::normalize($r->source))
                ->map(fn (self $r) => ['destination' => $r->destination, 'status_code' => $r->status_code])
                ->all(),
        );

        return $map[self::normalize($requestPath)] ?? null;
    }
}
