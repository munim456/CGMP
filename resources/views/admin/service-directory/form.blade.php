@extends('layouts.admin')

@section('page-title', $item->exists ? 'Edit directory item' : 'New directory item')

@section('content')
<form method="POST"
      action="{{ $item->exists ? route('admin.service-directory.update', $item) : route('admin.service-directory.store') }}"
      class="admin-form-layout">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="admin-form-main">
        <section class="admin-panel">
            <div class="field">
                <label for="title">Title <span class="req">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required>
            </div>
            <div class="field">
                <label for="body">Description</label>
                <textarea id="body" name="body" rows="6" maxlength="2000">{{ old('body', $item->body) }}</textarea>
                <p class="help">Shown inside the accordion when a visitor expands this item.</p>
            </div>
        </section>
    </div>

    <aside class="admin-form-side">
        <section class="admin-panel">
            <h2>Publish</h2>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))> Show on website</label>
            <div class="field mt-2">
                <label for="sort_order">Display order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="9999">
                <p class="help">Lower numbers appear first.</p>
            </div>
            <button type="submit" class="btn btn--primary btn--block btn--lg"><x-icon name="save"/> Save item</button>
        </section>

        <section class="admin-panel">
            <h2>Icon</h2>
            <div class="field">
                <select name="icon" id="icon">
                    @foreach(['stethoscope','heart-pulse','brain','baby','flower','dumbbell','activity','shield-check','briefcase-medical','user-round','users','clock','file-text','star','alert-triangle','eye','map-pin','accessibility'] as $iconName)
                        <option value="{{ $iconName }}" @selected(old('icon', $item->icon) === $iconName)>{{ ucfirst(str_replace('-', ' ', $iconName)) }}</option>
                    @endforeach
                </select>
            </div>
        </section>
    </aside>
</form>
@endsection
