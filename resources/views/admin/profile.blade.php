@extends('layouts.admin')

@section('page-title', 'My account')

@section('content')
<div class="admin-columns">
    <section class="admin-panel admin-panel--narrow">
        <h2>Your details</h2>
        <dl class="detail-list">
            <div><dt>Name</dt><dd>{{ auth()->user()->name }}</dd></div>
            <div><dt>Email</dt><dd>{{ auth()->user()->email }}</dd></div>
            <div><dt>Role</dt><dd>{{ ucfirst(auth()->user()->role) }}</dd></div>
        </dl>
    </section>

    <section class="admin-panel admin-panel--narrow">
        <h2>Change password</h2>
        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf @method('PUT')
            <div class="field">
                <label for="current_password">Current password</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
            </div>
            <div class="field">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <p class="help">At least 10 characters, with upper and lower case letters and a number.</p>
            </div>
            <div class="field">
                <label for="password_confirmation">Repeat new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn--primary"><x-icon name="save"/> Change password</button>
        </form>
    </section>
</div>
@endsection
