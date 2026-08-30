@extends('layouts.admin')

@section('page-title', 'Medical services directory')

@section('content')
<div class="admin-toolbar">
    <p class="muted">{{ $items->count() }} directory items — shown as the accordion list on the Services page.</p>
    <a href="{{ route('admin.service-directory.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> New item</a>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Order</th><th>Title</th><th>Icon</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->sort_order }}</td>
                <td>
                    <strong><a href="{{ route('admin.service-directory.edit', $item) }}">{{ $item->title }}</a></strong>
                    <p class="table-sub">{{ \Illuminate\Support\Str::limit($item->body, 80) }}</p>
                </td>
                <td><span class="icon-chip"><x-icon name="{{ $item->icon ?: 'stethoscope' }}"/></span></td>
                <td><span class="badge {{ $item->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $item->is_active ? 'Visible' : 'Hidden' }}</span></td>
                <td class="table-actions">
                    <a href="{{ route('admin.service-directory.edit', $item) }}" class="icon-btn" title="Edit"><x-icon name="pencil"/></a>
                    <form method="POST" action="{{ route('admin.service-directory.destroy', $item) }}" data-confirm="Delete this directory item?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No directory items yet. <a href="{{ route('admin.service-directory.create') }}">Add the first one →</a></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
