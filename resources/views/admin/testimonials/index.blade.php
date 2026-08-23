@extends('layouts.admin')

@section('page-title', 'Testimonials')

@section('content')
<div class="admin-toolbar">
    <p class="muted">Shown as a rotating carousel on the homepage. Keep quotes short and friendly.</p>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> Add testimonial</a>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Quote</th><th>Name</th><th>Rating</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($testimonials as $testimonial)
            <tr>
                <td>"{{ \Illuminate\Support\Str::limit($testimonial->content, 100) }}"@if($testimonial->context)<p class="table-sub">{{ $testimonial->context }}</p>@endif</td>
                <td>{{ $testimonial->name }}</td>
                <td>{{ str_repeat('★', $testimonial->rating) }}</td>
                <td><span class="badge {{ $testimonial->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $testimonial->is_active ? 'Shown' : 'Hidden' }}</span></td>
                <td class="table-actions">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="icon-btn" title="Edit"><x-icon name="pencil"/></a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" data-confirm="Delete this testimonial?">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 muted">No testimonials yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
