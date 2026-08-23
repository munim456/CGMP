@extends('layouts.admin')

@section('page-title', $announcement->exists ? 'Edit announcement' : 'New announcement')

@section('content')
<form method="POST"
      action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
      class="admin-form-layout admin-form-layout--single" enctype="multipart/form-data">
    @csrf
    @if($announcement->exists) @method('PUT') @endif

    <section class="admin-panel">
        <div class="field">
            <label for="title">Title (optional)</label>
            <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" maxlength="150">
        </div>
        <div class="field">
            <label for="message">Message shown to visitors <span class="req">*</span></label>
            <textarea id="message" name="message" rows="3" required maxlength="500">{{ old('message', $announcement->message) }}</textarea>
            <p class="help">Announcements with a photo appear as large panels on the home page; ones without appear in the notice bar.</p>
        </div>

        <div class="form-row">
            <div class="field">
                <label for="button_text">Button text (optional)</label>
                <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $announcement->button_text) }}" maxlength="60" placeholder="e.g. Find out more">
            </div>
            <div class="field">
                <label for="button_url">Button link (optional)</label>
                <input type="text" id="button_url" name="button_url" value="{{ old('button_url', $announcement->button_url) }}" placeholder="/services or https://...">
            </div>
        </div>

        <section class="field">
            <h2 class="text-base font-semibold mb-2">Photo (optional)</h2>
            @if($announcement->image)
                <img src="{{ image_url($announcement->image) }}" alt="" class="img-preview">
                <label class="check-label"><input type="checkbox" name="remove_image" value="1"> Remove current photo</label>
            @endif
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
            <p class="help">A wide landscape image works best.</p>
        </section>
        <div class="form-row">
            <div class="field">
                <label for="type">Style</label>
                <select id="type" name="type">
                    <option value="info" @selected(old('type', $announcement->type ?? 'info') === 'info')>Information (calm)</option>
                    <option value="warning" @selected(old('type', $announcement->type) === 'warning')>Warning (attention)</option>
                </select>
            </div>
            <label class="check-label check-label--block mt-2">
                <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $announcement->is_active))>
                Show this announcement on the website
            </label>
        </div>
        <div class="form-row">
            <div class="field">
                <label for="starts_at">Show from</label>
                <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="field">
                <label for="ends_at">Until (optional)</label>
                <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}">
                <p class="help">Leave empty to show until switched off.</p>
            </div>
        </div>
        <button type="submit" class="btn btn--primary btn--lg"><x-icon name="save"/> Save announcement</button>
    </section>
</form>
@endsection
