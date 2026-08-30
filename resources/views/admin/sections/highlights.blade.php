@extends('layouts.admin')

@section('page-title', 'Home highlights')

@section('content')
<form method="POST" action="{{ route('admin.sections.update', $key) }}" class="admin-form-layout admin-form-layout--single">
    @csrf @method('PUT')

    <section class="admin-panel">
        <p class="help mb-4">The row of feature cards near the top of the homepage. Add, remove or reorder freely.</p>

        <div id="highlight-rows">
            @foreach(old('items', $data['items'] ?? [['icon' => 'stethoscope', 'title' => '', 'text' => '']]) as $i => $item)
                <div class="repeater-row" data-repeater="highlight">
                    <div class="form-row form-row--tight">
                        <div class="field field--narrow">
                            <label>Icon</label>
                            <select name="items[{{ $i }}][icon]">
                                @foreach(['stethoscope','heart-pulse','activity','shield-check','briefcase-medical','user-round','clock','calendar-check'] as $iconName)
                                    <option value="{{ $iconName }}" @selected(($item['icon'] ?? '') === $iconName)>{{ ucfirst(str_replace('-', ' ', $iconName)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Title</label>
                            <input type="text" name="items[{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" maxlength="100" placeholder="Medical Treatment">
                        </div>
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="items[{{ $i }}][text]" rows="2" maxlength="300">{{ $item['text'] ?? '' }}</textarea>
                    </div>
                    <button type="button" class="btn btn--danger btn--sm repeater-remove">Remove card</button>
                </div>
            @endforeach
        </div>

        <template id="highlight-template">
            <div class="repeater-row" data-repeater="highlight">
                <div class="form-row form-row--tight">
                    <div class="field field--narrow">
                        <label>Icon</label>
                        <select name="items[__IDX__][icon]">
                            @foreach(['stethoscope','heart-pulse','activity','shield-check','briefcase-medical','user-round','clock','calendar-check'] as $iconName)
                                <option value="{{ $iconName }}">{{ ucfirst(str_replace('-', ' ', $iconName)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Title</label>
                        <input type="text" name="items[__IDX__][title]" maxlength="100" placeholder="Card title">
                    </div>
                </div>
                <div class="field">
                    <label>Description</label>
                    <textarea name="items[__IDX__][text]" rows="2" maxlength="300"></textarea>
                </div>
                <button type="button" class="btn btn--danger btn--sm repeater-remove">Remove card</button>
            </div>
        </template>

        <button type="button" class="btn btn--outline mt-2" id="add-highlight"><x-icon name="plus" class="w-4 h-4"/> Add another card</button>
        <div class="mt-4">
            <button type="submit" class="btn btn--primary btn--lg"><x-icon name="save"/> Save highlights</button>
        </div>
    </section>
</form>
@endsection
