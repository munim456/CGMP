@extends('layouts.admin')

@section('page-title', 'Messages')

@section('content')
<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>From</th><th>Message</th><th>Received</th><th></th></tr></thead>
        <tbody>
        @forelse($messages as $msg)
            <tr class="{{ !$msg->is_read ? 'row-unread' : '' }}">
                <td>
                    <strong>{{ $msg->name }}</strong>
                    <p class="table-sub">{{ $msg->email }}@if($msg->phone) · {{ $msg->phone }}@endif</p>
                </td>
                <td>{{ \Illuminate\Support\Str::limit($msg->message, 110) }}</td>
                <td>{{ $msg->created_at->format('j M Y, g:ia') }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.messages.show', $msg) }}" class="icon-btn" title="Open"><x-icon name="eye"/></a>
                    <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" data-confirm="Delete this message?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="p-4 muted">No messages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $messages->links() }}
@endsection
