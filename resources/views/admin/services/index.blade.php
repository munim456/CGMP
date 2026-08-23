@extends('layouts.admin')

@section('page-title', 'Services')

@section('content')
<div class="admin-toolbar">
    <p class="muted">{{ $services->count() }} services — drag order using the number field inside each service.</p>
    <a href="{{ route('admin.services.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> New service</a>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Order</th><th>Service</th><th>Icon</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($services as $service)
            <tr>
                <td>{{ $service->sort_order }}</td>
                <td>
                    <strong><a href="{{ route('admin.services.edit', $service) }}">{{ $service->title }}</a></strong>
                    <p class="table-sub">{{ \Illuminate\Support\Str::limit($service->short_description, 80) }}</p>
                </td>
                <td><span class="icon-chip"><x-icon name="{{ $service->icon ?: 'stethoscope' }}"/></span></td>
                <td><span class="badge {{ $service->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $service->is_active ? 'Visible' : 'Hidden' }}</span></td>
                <td class="table-actions">
                    <a href="{{ route('admin.services.edit', $service) }}" class="icon-btn" title="Edit"><x-icon name="pencil"/></a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" data-confirm="Delete this service?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No services yet. <a href="{{ route('admin.services.create') }}">Add the first one →</a></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
