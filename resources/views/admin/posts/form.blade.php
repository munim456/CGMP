@extends('layouts.admin')

@section('page-title', $post->exists ? 'Edit post' : 'New post')

@section('content')
<form method="POST" enctype="multipart/form-data"
      action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      class="admin-form-layout">
    @csrf
    @if($post->exists) @method('PUT') @endif

    <div class="admin-form-main">
        <section class="admin-panel">
            <div class="field">
                <label for="title">Title <span class="req">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required data-slug-source>
                <p class="help">The headline shown to readers and in search results.</p>
            </div>

            <div class="field">
                <label for="slug">Link address (slug)</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}"
                       placeholder="auto-generated-from-title" pattern="[a-z0-9\-]*" data-slug-target>
                <p class="help">Leave blank to generate automatically from the title. Lowercase letters, numbers, dashes.</p>
            </div>

            <div class="field">
                <label for="excerpt">Short summary</label>
                <textarea id="excerpt" name="excerpt" rows="2" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
                <p class="help">One or two sentences shown on blog cards. Leave blank to use the first part of the article.</p>
            </div>

            <div class="field">
                <label for="body">Article <span class="req">*</span></label>
                <textarea id="body" name="body" rows="18" class="rich-editor" required>{{ old('body', $post->body) }}</textarea>
                <p class="help">Use the toolbar to format text and insert images directly into the article.</p>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Search engine details</h2>
            <div class="field">
                <label for="meta_title">SEO title</label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="255">
                <p class="help"><span data-meta-count="meta_title"></span> Recommended: under 60 characters.</p>
            </div>
            <div class="field">
                <label for="meta_description">SEO description</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="400">{{ old('meta_description', $post->meta_description) }}</textarea>
                <p class="help"><span data-meta-count="meta_description"></span> Recommended: 120–160 characters.</p>
            </div>
        </section>
    </div>

    <aside class="admin-form-side">
        <section class="admin-panel">
            <h2>Publish</h2>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft — hidden from website</option>
                    <option value="published" @selected(old('status', $post->status ?? 'published') === 'published')>Published — visible</option>
                    @if($post->exists)
                        <option value="scheduled" @selected(old('status', $post->status) === 'scheduled')>Scheduled (publish date below)</option>
                    @endif
                </select>
            </div>
            <div class="field">
                <label for="published_at">Publish date</label>
                <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                <p class="help">Used when scheduling a post for the future.</p>
            </div>

            <button type="submit" class="btn btn--primary btn--block btn--lg">
                <x-icon name="save"/> {{ $post->exists ? 'Save changes' : 'Create post' }}
            </button>
            @if($post->exists)
                <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn--outline btn--block mt-2">
                    <x-icon name="eye"/> Preview on site</a>
            @endif
        </section>

        <section class="admin-panel">
            <h2>Featured image</h2>
            @if($post->featured_image)
                <img src="{{ image_url($post->featured_image) }}" alt="" class="img-preview">
                <label class="check-label"><input type="checkbox" name="remove_featured_image" value="1"> Remove current image</label>
            @else
                <div class="img-placeholder img-placeholder--form"><x-icon name="image" class="w-8 h-8"/></div>
            @endif
            <div class="field mt-2">
                <input type="file" id="featured_image" name="featured_image" accept=".jpg,.jpeg,.png,.webp,.gif,.svg" data-image-preview>
                <p class="help">JPG, PNG or WebP. Large images are resized automatically.</p>
            </div>
            <div class="field">
                <label for="featured_image_alt">Image description (alt text)</label>
                <input type="text" id="featured_image_alt" name="featured_image_alt" value="{{ old('featured_image_alt', $post->featured_image_alt) }}">
                <p class="help">Describe the image for visually impaired visitors.</p>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Category & tags</h2>
            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">— None —</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $post->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <p class="help">Manage categories under Content → Categories & tags.</p>
            </div>
            <div class="field">
                <label for="tags_input">Tags</label>
                <input type="text" id="tags_input" name="tags_input"
                       value="{{ old('tags_input', $post->tags->pluck('name')->implode(', ')) }}"
                       placeholder="diabetes, flu, mental-health" list="all-tags">
                <datalist id="all-tags">
                    @foreach($allTags as $tagName)<option value="{{ $tagName }}"></option>@endforeach
                </datalist>
                <p class="help">Separate tags with commas.</p>
            </div>
        </section>
    </aside>
</form>
@endsection
