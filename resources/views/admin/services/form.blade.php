@extends('layouts.admin')

@section('page-title', $service->exists ? 'Edit service' : 'New service')

@section('content')
<form method="POST" enctype="multipart/form-data"
      action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
      class="admin-form-layout">
    @csrf
    @if($service->exists) @method('PUT') @endif

    <div class="admin-form-main">
        <section class="admin-panel">
            <div class="field">
                <label for="title">Service name <span class="req">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $service->title) }}" required data-slug-source>
            </div>
            <div class="field">
                <label for="slug">Link address (slug)</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $service->slug) }}" pattern="[a-z0-9\-]*" data-slug-target placeholder="auto-generated">
            </div>
            <div class="field">
                <label for="short_description">Short summary</label>
                <textarea id="short_description" name="short_description" rows="2" maxlength="400">{{ old('short_description', $service->short_description) }}</textarea>
                <p class="help">Shown on cards and listings. Keep it to a sentence or two.</p>
            </div>
            <div class="field">
                <label for="description">Full description</label>
                <textarea id="description" name="description" rows="12" class="rich-editor">{{ old('description', $service->description) }}</textarea>
                <p class="help">Shown on the service's own page.</p>
            </div>
        </section>
    </div>

    <aside class="admin-form-side">
        <section class="admin-panel">
            <h2>Publish</h2>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->exists ? $service->is_active : true))> Show on website</label>
            <div class="field mt-2">
                <label for="sort_order">Display order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0" max="9999">
                <p class="help">Lower numbers appear first.</p>
            </div>
            <button type="submit" class="btn btn--primary btn--block btn--lg"><x-icon name="save"/> Save service</button>
        </section>

        <section class="admin-panel">
            <h2>Icon</h2>
            <div class="field">
                <select name="icon" id="icon">
                    @foreach(['stethoscope','heart-pulse','brain','baby','flower','dumbbell','activity','shield-check','briefcase-medical','user-round','clock'] as $iconName)
                        <option value="{{ $iconName }}" @selected(old('icon', $service->icon) === $iconName)>@ucfirst(str_replace('-', ' ', $iconName))</option>
                    @endforeach
                </select>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Image (optional)</h2>
            @if($service->image)
                <img src="{{ image_url($service->image) }}" alt="" class="img-preview">
                <label class="check-label"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
            @endif
            <div class="field mt-2">
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
                <p class="help">JPG, PNG or WebP, resized automatically.</p>
            </div>
        </section>
    </aside>
</form>
@endsection
