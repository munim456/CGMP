<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class MediaUploader
{
    public const ALLOWED_MIMES = 'jpg,jpeg,png,webp,gif,svg';

    public static function handle(UploadedFile $file, string $directory = 'media', ?int $maxWidth = 1600, bool $record = true): array
    {
        $directory = trim($directory, '/');
        $filename = now()->format('YmdHis') . '-' . \Illuminate\Support\Str::random(6) . '.' . strtolower($file->getClientOriginalExtension() ?: 'bin');
        $path = $file->storeAs($directory, $filename, 'public');

        if (! in_array(strtolower($file->getClientOriginalExtension()), ['svg'])) {
            self::optimize($path, $maxWidth);
        }

        if ($record) {
            Media::create([
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => (int) (Storage::disk('public')->size($path) ?? 0),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        return ['path' => $path];
    }

    protected static function optimize(string $path, int $maxWidth): void
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            $manager = new ImageManager(new GdDriver());
            $image = $manager->read($fullPath);

            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            $image->save($fullPath, quality: 82);
        } catch (\Throwable) {
            return;
        }
    }

    public static function delete(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
