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

    public static function handle(UploadedFile $file, string $directory = 'media', ?int $maxWidth = 1600, bool $record = true, ?int $thumbWidth = null): array
    {
        $directory = trim($directory, '/');
        $filename = now()->format('YmdHis') . '-' . \Illuminate\Support\Str::random(6) . '.' . strtolower($file->getClientOriginalExtension() ?: 'bin');
        $path = $file->storeAs($directory, $filename, 'public');
        $isSvg = strtolower($file->getClientOriginalExtension()) === 'svg';

        if (! $isSvg) {
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

        $result = ['path' => $path];

        if ($thumbWidth && ! $isSvg) {
            $result['thumb_path'] = self::createThumb($path, $directory, $thumbWidth);
        }

        return $result;
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

    protected static function createThumb(string $path, string $directory, int $thumbWidth): ?string
    {
        try {
            $manager = new ImageManager(new GdDriver());
            $image = $manager->read(Storage::disk('public')->path($path));
            $image->scaleDown(width: $thumbWidth);

            $thumbFilename = pathinfo($path, PATHINFO_FILENAME) . '-thumb.' . pathinfo($path, PATHINFO_EXTENSION);
            $thumbPath = $directory . '/' . $thumbFilename;
            $image->save(Storage::disk('public')->path($thumbPath), quality: 82);

            return $thumbPath;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function delete(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
