@extends('layouts.admin')

@section('page-title', 'Announcements')

@section('content')
<div class="admin-toolbar">
    <p class="muted">Active notices show as a banner at the top of every page. Visitors can dismiss them.</p>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> New announcement</a>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Message</th><th>Type</th><th>Schedule</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($announcements as $announcement)
            <tr>
                <td><strong>{{ $announcement->title ?: 'Notice' }}</strong><p class="table-sub">{{ \Illuminate\Support\Str::limit($announcement->message, 90) }}</p></td>
                <td><span class="badge {{ $announcement->type === 'warning' ? 'badge--warning' : 'badge--info' }}">{{ $announcement->type }}</span></td>
                <td class="table-sub">
                    {{ $announcement->starts_at?->format('j M Y') ?? 'No start' }} →
                    {{ $announcement->ends_at?->format('j M Y') ?? 'no end' }}
                </td>
                <td><span class="badge {{ $announcement->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $announcement->is_active ? 'On' : 'Off' }}</span></td>
                <td class="table-actions">
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="icon-btn" title="Edit"><x-icon name="pencil"/></a>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" data-confirm="Delete this announcement?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No announcements yet. <a href="{{ route('admin.announcements.create') }}">Create one →</a></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
