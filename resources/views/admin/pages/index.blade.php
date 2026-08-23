@extends('layouts.admin')

@section('page-title', 'Static pages')

@section('content')
<p class="muted mb-4">These pages have fixed addresses on the website. Click a page to edit its text.</p>

<div class="table-wrap">
    <table class="admin-table">
        <thead><tr><th>Page</th><th>Address</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td><strong>{{ $page->title }}</strong></td>
                <td><code>/{{ $page->slug }}</code></td>
                <td><span class="badge {{ $page->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $page->is_active ? 'Visible' : 'Hidden' }}</span></td>
                <td class="table-actions"><a href="{{ route('admin.pages.edit', $page) }}" class="btn btn--outline btn--sm">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
