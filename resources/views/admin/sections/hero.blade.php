@extends('layouts.admin')

@section('page-title', 'Hero section')

@section('content')
<form method="POST" action="{{ route('admin.sections.update', $key) }}" enctype="multipart/form-data" class="admin-form-layout admin-form-layout--single">
    @csrf @method('PUT')

    <section class="admin-panel">
        <p class="help mb-4">This is the big banner at the very top of the homepage.</p>

        <div class="field">
            <label for="heading">Main heading <span class="req">*</span></label>
            <input type="text" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" required maxlength="200">
        </div>
        <div class="field">
            <label for="subheading">Sub-heading</label>
            <textarea id="subheading" name="subheading" rows="3" maxlength="400">{{ old('subheading', $data['subheading'] ?? '') }}</textarea>
        </div>
        <div class="field">
            <label for="badge_text">Small badge above heading</label>
            <input type="text" id="badge_text" name="badge_text" value="{{ old('badge_text', $data['badge_text'] ?? '') }}" placeholder="Walk-ins welcome" maxlength="80">
        </div>

        <h2 class="mt-4">Buttons</h2>
        <div class="form-row">
            <div class="field">
                <label for="primary_button_text">Primary button label</label>
                <input type="text" id="primary_button_text" name="primary_button_text" value="{{ old('primary_button_text', $data['primary_button_text'] ?? '') }}" maxlength="60" placeholder="Book Appointment">
                <label class="mt-2" for="primary_button_link">Primary link (full URL or leave blank for the booking page)</label>
                <input type="text" id="primary_button_link" name="primary_button_link" value="{{ old('primary_button_link', $data['primary_button_link'] ?? '') }}">
            </div>
            <div class="field">
                <label for="secondary_button_text">Secondary button label</label>
                <input type="text" id="secondary_button_text" name="secondary_button_text" value="{{ old('secondary_button_text', $data['secondary_button_text'] ?? '') }}" maxlength="60" placeholder="Our Services">
                <label class="mt-2" for="secondary_button_link">Secondary link (e.g. /services)</label>
                <input type="text" id="secondary_button_link" name="secondary_button_link" value="{{ old('secondary_button_link', $data['secondary_button_link'] ?? '') }}">
            </div>
        </div>

        <h2 class="mt-4">Background image</h2>
        @if(!empty($data['image']))
            <img src="{{ image_url($data['image']) }}" alt="" class="img-preview img-preview--wide">
            <label class="check-label"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
        @else
            <div class="img-placeholder img-placeholder--form"><x-icon name="image" class="w-8 h-8"/></div>
        @endif
        <div class="field mt-2">
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
            <p class="help">A wide clinic photo works best — around 1920×900 pixels.</p>
        </div>

        <button type="submit" class="btn btn--primary btn--lg"><x-icon name="save"/> Save hero section</button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn--outline btn--lg ml-2"><x-icon name="eye"/> Preview homepage</a>
    </section>
</form>
@endsection
