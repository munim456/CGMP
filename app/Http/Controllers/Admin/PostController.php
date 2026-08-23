<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\Tag;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.posts.index', [
            'posts' => Post::query()
                ->with(['category', 'tags'])
                ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->latest('updated_at')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Post());
    }

    public function store(Request $request): RedirectResponse
    {
        $post = new Post();
        $this->save($post, $request);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', __('Post created successfully.'));
    }

    public function edit(Post $post): View
    {
        return $this->form($post);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $wasPublished = $post->status === 'published';
        $this->save($post, $request);

        if (! $wasPublished && $post->status === 'published') {
            session()->flash('status', __('Post published successfully.'));
        } else {
            session()->flash('status', __('Post updated successfully.'));
        }

        return redirect()->route('admin.posts.edit', $post);
    }

    public function togglePublish(Post $post): RedirectResponse
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft']);
            session()->flash('status', __('Post moved to drafts.'));
        } else {
            $post->update([
                'status' => 'published',
                'published_at' => $post->published_at ?? now(),
            ]);
            session()->flash('status', __('Post published successfully.'));
        }

        return back();
    }

    public function destroy(Post $post): RedirectResponse
    {
        MediaUploader::delete($post->featured_image);
        $post->tags()->detach();
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', __('Post deleted.'));
    }

    protected function form(Post $post): View
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::query()->orderBy('name')->get(),
            'allTags' => Tag::query()->orderBy('name')->pluck('name'),
        ]);
    }

    protected function save(Post $post, Request $request): void
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags_input' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:400'],
            'featured_image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
            'remove_featured_image' => ['nullable', 'boolean'],
        ]);

        $data = [
            'author_id' => $post->author_id ?? $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['slug'] ?: $validated['title'], $post->id),
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'featured_image_alt' => $validated['featured_image_alt'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ];

        if ($data['status'] === 'published') {
            $data['published_at'] = $post->published_at ?? ($validated['published_at'] ?? now());
        } elseif ($data['status'] === 'scheduled' && ! empty($validated['published_at'])) {
            $data['status'] = 'draft';
            $data['publish_at_scheduled'] = true;
            $data['published_at'] = $validated['published_at'];
        }

        if ($request->boolean('remove_featured_image')) {
            MediaUploader::delete($post->featured_image);
            $data['featured_image'] = null;
        } elseif ($request->hasFile('featured_image')) {
            MediaUploader::delete($post->featured_image);
            $data['featured_image'] = MediaUploader::handle($request->file('featured_image'), 'media/posts')['path'];
        }

        $post->update($data);

        $tagIds = [];
        foreach (array_filter(array_map('trim', explode(',', (string) $request->input('tags_input')))) as $tagName) {
            $tagIds[] = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            )->id;
        }
        $post->tags()->sync($tagIds);
    }

    protected function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $attempt = 1;

        while (Post::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . ++$attempt;
        }

        return $slug;
    }
}
