<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::query()->latest('updated_at')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Announcement());
    }

    public function store(Request $request): RedirectResponse
    {
        Announcement::create($this->validated($request));

        return redirect()->route('admin.announcements.index')->with('status', __('Announcement created.'));
    }    public function edit(Announcement $announcement): View
    {
        return $this->form($announcement);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($this->validated($request, $announcement));

        return redirect()->route('admin.announcements.index')->with('status', __('Announcement updated.'));
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('status', __('Announcement deleted.'));
    }

    protected function form(Announcement $announcement): View
    {
        return view('admin.announcements.form', ['announcement' => $announcement]);
    }

    protected function validated(Request $request, ?Announcement $announcement = null): array
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:info,warning'],
            'image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $data['button_text'] = $data['button_text'] ?? null;
        $data['button_url'] = $data['button_url'] ?? null;

        if ($request->boolean('remove_image')) {
            MediaUploader::delete($announcement?->image);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            MediaUploader::delete($announcement?->image);
            $data['image'] = MediaUploader::handle($request->file('image'), 'media/announcements')['path'];
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['starts_at'] = $data['starts_at'] ?? null;
        $data['ends_at'] = $data['ends_at'] ?? null;

        return $data;
    }
}
