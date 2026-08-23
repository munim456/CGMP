@extends('layouts.admin')

@section('page-title', 'Booking strip')

@section('content')
<form method="POST" action="{{ route('admin.sections.update', $key) }}" class="admin-form-layout admin-form-layout--single">
    @csrf @method('PUT')

    <section class="admin-panel">
        <p class="help mb-4">The coloured band near the bottom of most pages that encourages patients to book.</p>

        <div class="field">
            <label for="heading">Heading <span class="req">*</span></label>
            <input type="text" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" required maxlength="200" placeholder="Ready to see a doctor?">
        </div>
        <div class="field">
            <label for="text">Supporting text</label>
            <textarea id="text" name="text" rows="2" maxlength="300">{{ old('text', $data['text'] ?? '') }}</textarea>
        </div>
        <div class="field">
            <label for="button_text">Button label</label>
            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $data['button_text'] ?? '') }}" maxlength="60" placeholder="Book online with HealthEngine">
            <p class="help">The button always opens the booking page, which uses your HealthEngine link from Site settings.</p>
        </div>

        <button type="submit" class="btn btn--primary btn--lg"><x-icon name="save"/> Save booking strip</button>
    </section>
</form>
@endsection
