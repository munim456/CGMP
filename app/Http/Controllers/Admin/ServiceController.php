<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Service());
    }

    public function store(Request $request): RedirectResponse
    {
        $service = new Service();
        $this->save($service, $request);

        return redirect()->route('admin.services.edit', $service)->with('status', __('Service created.'));
    }

    public function edit(Service $service): View
    {
        return $this->form($service);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->save($service, $request);

        return redirect()->route('admin.services.edit', $service)->with('status', __('Service updated.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        MediaUploader::delete($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', __('Service deleted.'));
    }

    protected function form(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    protected function save(Service $service, Request $request): void
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i'],
            'icon' => ['nullable', 'string', 'max:50'],
            'short_description' => ['nullable', 'string', 'max:400'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $data = [
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['slug'] ?: $validated['title'], $service->id),
            'icon' => $validated['icon'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->boolean('remove_image')) {
            MediaUploader::delete($service->image);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            MediaUploader::delete($service->image);
            $data['image'] = MediaUploader::handle($request->file('image'), 'media/services')['path'];
        }

        $service->update($data);
    }

    protected function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $attempt = 1;

        while (Service::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$attempt;
        }

        return $slug;
    }
}
