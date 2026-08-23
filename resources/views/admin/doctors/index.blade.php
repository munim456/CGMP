@extends('layouts.admin')

@section('page-title', 'Doctors')

@section('content')
<div class="admin-toolbar">
    <p class="muted">{{ $doctors->count() }} doctor profiles.</p>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn--primary"><x-icon name="plus" class="w-4 h-4"/> Add doctor</a>
</div>

<div class="card-grid-admin">
    @forelse($doctors as $doctor)
        <article class="doctor-admin-card {{ !$doctor->is_active ? 'is-hidden-item' : '' }}">
            <div class="doctor-admin-card__photo">
                @if($doctor->photo)
                    <img src="{{ image_url($doctor->photo) }}" alt="">
                @else
                    <span class="avatar-initials avatar-initials--lg">{{ collect(explode(' ', $doctor->name))->map(fn ($w) => mb_substr($w, 0, 1))->implode('') }}</span>
                @endif
            </div>
            <h3>{{ $doctor->name }}</h3>
            <p class="muted">{{ $doctor->role }}</p>
            <p class="table-sub">{{ $doctor->qualifications }}</p>
            <div class="doctor-admin-card__foot">
                <span class="badge {{ $doctor->is_active ? 'badge--success' : 'badge--neutral' }}">{{ $doctor->is_active ? 'Visible' : 'Hidden' }}</span>
                <span class="muted">Order: {{ $doctor->sort_order }}</span>
            </div>
            <div class="doctor-admin-card__actions">
                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn--outline btn--sm"><x-icon name="pencil" class="w-4 h-4"/> Edit</a>
                <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" data-confirm="Delete this doctor profile?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn--danger btn--sm"><x-icon name="trash" class="w-4 h-4"/> Delete</button>
                </form>
            </div>
        </article>
    @empty
        <p class="muted">No doctors yet. <a href="{{ route('admin.doctors.create') }}">Add the first profile →</a></p>
    @endforelse
</div>
@endsection
