<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:60']]);

        Tag::firstOrCreate(
            ['slug' => Str::slug($validated['name'])],
            ['name' => $validated['name']]
        );

        return back()->with('status', __('Tag saved.'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:60']]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('status', __('Tag updated.'));
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->posts()->detach();
        $tag->delete();

        return back()->with('status', __('Tag deleted.'));
    }
}
