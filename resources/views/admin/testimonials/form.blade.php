@extends('layouts.admin')

@section('page-title', $testimonial->exists ? 'Edit testimonial' : 'Add testimonial')

@section('content')
<form method="POST"
      action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
      class="admin-form-layout admin-form-layout--single">
    @csrf
    @if($testimonial->exists) @method('PUT') @endif

    <section class="admin-panel">
        <div class="field">
            <label for="name">Patient name or initials <span class="req">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required maxlength="120">
        </div>
        <div class="field">
            <label for="context">Context (optional)</label>
            <input type="text" id="context" name="context" value="{{ old('context', $testimonial->context) }}" maxlength="150" placeholder="Long-time patient">
        </div>
        <div class="field">
            <label for="content">Quote <span class="req">*</span></label>
            <textarea id="content" name="content" rows="4" required maxlength="1000">{{ old('content', $testimonial->content) }}</textarea>
        </div>
        <div class="form-row">
            <div class="field">
                <label for="rating">Rating</label>
                <select id="rating" name="rating">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((string) old('rating', $testimonial->rating ?? 5) === (string) $i)>@for($j = 0; $j < $i; $j++)★@endfor ({{ $i }}/5)</option>
                    @endfor
                </select>
            </div>
            <label class="check-label check-label--block mt-2">
                <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $testimonial->exists ? $testimonial->is_active : true))>
                Show on website
            </label>
        </div>
        <button type="submit" class="btn btn--primary btn--lg"><x-icon name="save"/> Save testimonial</button>
    </section>
</form>
@endsection
