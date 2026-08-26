@extends('layouts.admin')

@section('page-title', $doctor->exists ? 'Edit doctor' : 'Add doctor')

@section('content')
<form method="POST" enctype="multipart/form-data"
      action="{{ $doctor->exists ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}"
      class="admin-form-layout">
    @csrf
    @if($doctor->exists) @method('PUT') @endif

    <div class="admin-form-main">
        <section class="admin-panel">
            <div class="field">
                <label for="name">Doctor's name <span class="req">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $doctor->name) }}" required>
            </div>
            <div class="field">
                <label for="role">Role / title</label>
                <input type="text" id="role" name="role" value="{{ old('role', $doctor->role) }}" placeholder="Practice Principal">
            </div>
            <div class="field">
                <label for="qualifications">Qualifications</label>
                <input type="text" id="qualifications" name="qualifications" value="{{ old('qualifications', $doctor->qualifications) }}" placeholder="MBBS, FRACGP">
            </div>
            <div class="field">
                <label for="special_interests">Special interests</label>
                <input type="text" id="special_interests" name="special_interests" value="{{ old('special_interests', $doctor->special_interests) }}" placeholder="Women's health, Chronic disease management">
                <p class="help">Shown in the doctors directory table. Separate with commas.</p>
            </div>
            <div class="field">
                <label for="bio">Biography</label>
                <textarea id="bio" name="bio" rows="10" class="rich-editor">{{ old('bio', $doctor->bio) }}</textarea>
            </div>
        </section>
    </div>

    <aside class="admin-form-side">
        <section class="admin-panel">
            <h2>Publish</h2>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $doctor->exists ? $doctor->is_active : true))> Show on website</label>
            <div class="field mt-2">
                <label for="sort_order">Display order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $doctor->sort_order ?? 0) }}" min="0" max="9999">
            </div>
            <button type="submit" class="btn btn--primary btn--block btn--lg"><x-icon name="save"/> Save doctor</button>
        </section>

        <section class="admin-panel">
            <h2>Photo</h2>
            @if($doctor->photo)
                <img src="{{ image_url($doctor->photo) }}" alt="" class="img-preview img-preview--round">
                <label class="check-label"><input type="checkbox" name="remove_photo" value="1"> Remove current photo</label>
            @else
                <div class="img-placeholder img-placeholder--form"><x-icon name="user-round" class="w-8 h-8"/></div>
            @endif
            <div class="field mt-2">
                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp,.gif,.svg" data-image-preview>
                <p class="help">A square photo works best.</p>
            </div>
        </section>
    </aside>
</form>
@endsection
