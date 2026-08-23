@extends('layouts.admin')

@section('page-title', 'Edit page: ' . $page->title)

@section('content')
<form method="POST" action="{{ route('admin.pages.update', $page) }}" class="admin-form-layout">
    @csrf @method('PUT')

    <div class="admin-form-main">
        <section class="admin-panel">
            <div class="field">
                <label for="title">Page title <span class="req">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            </div>
            <div class="field">
                <label for="subtitle">Sub-title (shown under the heading)</label>
                <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" maxlength="300">
            </div>
            <div class="field">
                <label for="body">Page content</label>
                <textarea id="body" name="body" rows="22" class="rich-editor">{{ old('body', $page->body) }}</textarea>
                <p class="help">Address: <code>/{{ $page->slug }}</code></p>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Search engine details</h2>
            <div class="field">
                <label for="meta_title">SEO title</label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="255">
            </div>
            <div class="field">
                <label for="meta_description">SEO description</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="400">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>
        </section>
    </div>

    <aside class="admin-form-side">
        <section class="admin-panel">
            <h2>Publish</h2>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $page->is_active))> Show on website</label>
            <button type="submit" class="btn btn--primary btn--block btn--lg mt-3"><x-icon name="save"/> Save page</button>
        </section>
    </aside>
</form>
@endsection
