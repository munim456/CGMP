<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', [
            'media' => Media::query()->latest()->paginate(24),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files.*' => ['required', 'file', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:6144'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($request->file('files', []) as $file) {
            $result = MediaUploader::handle($file, 'media', 1600, false);
            Media::create([
                'filename' => $file->getClientOriginalName(),
                'path' => $result['path'],
                'alt_text' => $request->input('alt_text'),
                'size' => (int) (\Illuminate\Support\Facades\Storage::disk('public')->size($result['path']) ?? 0),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        return back()->with('status', __('Upload complete.'));
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $validated = $request->validate(['alt_text' => ['nullable', 'string', 'max:255']]);
        $media->update($validated);

        return back()->with('status', __('Image details updated.'));
    }

    public function destroy(Media $media): RedirectResponse
    {
        MediaUploader::delete($media->path);
        $media->delete();

        return back()->with('status', __('Image deleted from library.'));
    }
}
