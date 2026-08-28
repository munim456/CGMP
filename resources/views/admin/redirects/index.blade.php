@extends('layouts.admin')

@section('page-title', 'Redirects')

@section('content')
<div class="admin-toolbar">
    <p class="muted">Send visitors from an old URL (e.g. from the previous website) to a current page.
        Matched automatically before the 404 page is shown.</p>
</div>

<section class="admin-panel">
    <div class="admin-panel__head"><h2>Add a redirect</h2></div>
    <form method="POST" action="{{ route('admin.redirects.store') }}" class="form-row form-row--tight">
        @csrf
        <div class="field">
            <label>Old path</label>
            <input type="text" name="source" placeholder="/contact-us.html" required maxlength="2048">
        </div>
        <div class="field">
            <label>Redirect to</label>
            <input type="text" name="destination" placeholder="/contact or https://…" required maxlength="2048">
        </div>
        <div class="field field--narrow">
            <label>Type</label>
            <select name="status_code">
                <option value="301">301 (permanent)</option>
                <option value="302">302 (temporary)</option>
            </select>
        </div>
        <div class="field field--narrow field--checkbox">
            <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
        </div>
        <button type="submit" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> Add</button>
    </form>
</section>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Old path</th><th>Redirects to</th><th>Type</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($redirects as $redirect)
            @php($formId = 'redirect-form-'.$redirect->id)
            <tr>
                <td><input form="{{ $formId }}" type="text" name="source" value="{{ $redirect->source }}" maxlength="2048" aria-label="Old path"></td>
                <td><input form="{{ $formId }}" type="text" name="destination" value="{{ $redirect->destination }}" maxlength="2048" aria-label="Redirect to"></td>
                <td>
                    <select form="{{ $formId }}" name="status_code" aria-label="Redirect type">
                        <option value="301" @selected($redirect->status_code === 301)>301</option>
                        <option value="302" @selected($redirect->status_code === 302)>302</option>
                    </select>
                </td>
                <td><label><input form="{{ $formId }}" type="checkbox" name="is_active" value="1" @checked($redirect->is_active)> Active</label></td>
                <td class="table-actions">
                    <form id="{{ $formId }}" method="POST" action="{{ route('admin.redirects.update', $redirect) }}">
                        @csrf @method('PUT')
                    </form>
                    <button type="submit" form="{{ $formId }}" class="icon-btn" title="Save"><x-icon name="save"/></button>
                    <form method="POST" action="{{ route('admin.redirects.destroy', $redirect) }}" data-confirm="Delete this redirect?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No redirects yet. Add one above when a page moves or an old URL needs to keep working.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
