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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.posts.index', [
            'posts' => Post::query()
                ->with(['category', 'tags'])
                ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
                ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
                ->when($request->input('category'), fn ($q, $categoryId) => $q->where('category_id', $categoryId))
                ->latest('updated_at')
                ->paginate(15)
                ->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function preview(Request $request, Post $post): View
    {
        abort_unless($request->hasValidSignature(), 403);

        $post->load(['category', 'tags', 'author']);

        $related = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', ['post' => $post, 'related' => $related, 'isPreview' => true]);
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

    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
        ]);

        $uploaded = MediaUploader::handle($request->file('file'), 'media/posts/inline');

        return response()->json(['location' => image_url($uploaded['path'])]);
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
            'scheduled_for' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:400'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
            'remove_featured_image' => ['nullable', 'boolean'],
        ]);

        $willHaveImage = $request->hasFile('featured_image')
            || ($post->featured_image && ! $request->boolean('remove_featured_image'));

        $this->validateForStatus($validated, $willHaveImage);

        $data = [
            'author_id' => $post->author_id ?? $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug(($validated['slug'] ?? null) ?: $validated['title'], $post->id),
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'featured_image_alt' => $validated['featured_image_alt'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'scheduled_for' => null,
        ];

        if ($data['status'] === 'published') {
            $data['published_at'] = $post->published_at ?? ($validated['published_at'] ?? now());
        } elseif ($data['status'] === 'scheduled') {
            $data['scheduled_for'] = $validated['scheduled_for'];
            $data['published_at'] = null;
        } else {
            $data['published_at'] = null;
        }

        if ($request->boolean('remove_featured_image')) {
            MediaUploader::delete($post->featured_image);
            MediaUploader::delete($post->featured_image_thumb);
            $data['featured_image'] = null;
            $data['featured_image_thumb'] = null;
        } elseif ($request->hasFile('featured_image')) {
            MediaUploader::delete($post->featured_image);
            MediaUploader::delete($post->featured_image_thumb);
            $uploaded = MediaUploader::handle($request->file('featured_image'), 'media/posts', 1600, true, 400);
            $data['featured_image'] = $uploaded['path'];
            $data['featured_image_thumb'] = $uploaded['thumb_path'] ?? null;
        }

        $post->fill($data)->save();

        $tagIds = [];
        foreach (array_filter(array_map('trim', explode(',', (string) $request->input('tags_input')))) as $tagName) {
            $tagIds[] = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            )->id;
        }
        $post->tags()->sync($tagIds);
    }

    protected function validateForStatus(array $validated, bool $willHaveImage): void
    {
        $errors = [];

        if ($validated['status'] === 'published') {
            if (! $willHaveImage) {
                $errors['featured_image'] = 'A featured image is required to publish a post.';
            }
            if (empty($validated['featured_image_alt'])) {
                $errors['featured_image_alt'] = 'Image description (alt text) is required to publish a post.';
            }
            if (empty($validated['category_id'])) {
                $errors['category_id'] = 'A category is required to publish a post.';
            }
        }

        if ($validated['status'] === 'scheduled') {
            if (empty($validated['scheduled_for'])) {
                $errors['scheduled_for'] = 'A scheduled publish date is required.';
            } elseif (Carbon::parse($validated['scheduled_for'])->isPast()) {
                $errors['scheduled_for'] = 'The scheduled date must be in the future.';
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
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
