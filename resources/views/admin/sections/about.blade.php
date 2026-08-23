@extends('layouts.admin')

@section('page-title', 'About block & stats')

@section('content')
<form method="POST" action="{{ route('admin.sections.update', $key) }}" enctype="multipart/form-data" class="admin-form-layout admin-form-layout--single">
    @csrf @method('PUT')

    <section class="admin-panel">
        <p class="help mb-4">The "About the practice" section shown in the middle of the homepage, including the animated counters.</p>

        <div class="field">
            <label for="heading">Heading <span class="req">*</span></label>
            <input type="text" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" required maxlength="200">
        </div>
        <div class="field">
            <label for="body">Text <span class="req">*</span></label>
            <textarea id="body" name="body" rows="6" class="rich-editor" required>{{ old('body', $data['body'] ?? '') }}</textarea>
        </div>
        <div class="field">
            <label>Key points (shown as ticks)</label>
            @foreach(range(0, 4) as $i)
                <input type="text" name="points[]" value="{{ old('points.' . $i, $data['points'][$i] ?? '') }}"
                       maxlength="120" placeholder="{{ ['Open five days a week','Same-day appointments','Walk-ins welcome'][$i] ?? 'Another key point' }}"
                       class="mt-1">
            @endforeach
        </div>

        <h2 class="mt-4">Counters</h2>
        <p class="help">Numbers count up when visitors scroll to them. Leave a row's label empty to hide it.</p>

        <div id="stat-rows">
            @foreach(old('stats', $data['stats'] ?? [['value' => '', 'suffix' => '+', 'label' => ''], ['value' => '', 'suffix' => '', 'label' => ''], ['value' => '', 'suffix' => '', 'label' => '']]) as $i => $stat)
                <div class="form-row form-row--tight stat-row">
                    <div class="field field--xs"><label>Number</label><input type="number" name="stats[{{ $i }}][value]" value="{{ $stat['value'] ?? '' }}" min="0"></div>
                    <div class="field field--xs"><label>Suffix</label><input type="text" name="stats[{{ $i }}][suffix]" value="{{ $stat['suffix'] ?? '' }}" placeholder="+" maxlength="5"></div>
                    <div class="field"><label>Label</label><input type="text" name="stats[{{ $i }}][label]" value="{{ $stat['label'] ?? '' }}" placeholder="Years serving the community" maxlength="80"></div>
                </div>
            @endforeach
        </div>

        <h2 class="mt-4">Image</h2>
        @if(!empty($data['image']))
            <img src="{{ image_url($data['image']) }}" alt="" class="img-preview img-preview--wide">
            <label class="check-label"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
        @endif
        <div class="field mt-2">
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,.svg">
        </div>

        <button type="submit" class="btn btn--primary btn--lg mt-2"><x-icon name="save"/> Save about block</button>
    </section>
</form>
@endsection
