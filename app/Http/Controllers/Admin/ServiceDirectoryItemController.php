<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceDirectoryItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceDirectoryItemController extends Controller
{
    public function index(): View
    {
        return view('admin.service-directory.index', [
            'items' => ServiceDirectoryItem::query()->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new ServiceDirectoryItem());
    }

    public function store(Request $request): RedirectResponse
    {
        $item = new ServiceDirectoryItem();
        $this->save($item, $request);

        return redirect()->route('admin.service-directory.edit', $item)->with('status', __('Directory item created.'));
    }

    public function edit(ServiceDirectoryItem $serviceDirectoryItem): View
    {
        return $this->form($serviceDirectoryItem);
    }

    public function update(Request $request, ServiceDirectoryItem $serviceDirectoryItem): RedirectResponse
    {
        $this->save($serviceDirectoryItem, $request);

        return redirect()->route('admin.service-directory.edit', $serviceDirectoryItem)->with('status', __('Directory item updated.'));
    }

    public function destroy(ServiceDirectoryItem $serviceDirectoryItem): RedirectResponse
    {
        $serviceDirectoryItem->delete();

        return redirect()->route('admin.service-directory.index')->with('status', __('Directory item deleted.'));
    }

    protected function form(ServiceDirectoryItem $item): View
    {
        return view('admin.service-directory.form', ['item' => $item]);
    }

    protected function save(ServiceDirectoryItem $item, Request $request): void
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:50'],
            'body' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $item->fill([
            'title' => $validated['title'],
            'icon' => $validated['icon'] ?? null,
            'body' => $validated['body'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ])->save();
    }
}
