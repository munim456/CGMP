@extends('layouts.admin')

@section('layout-content')
<div class="login-screen">
    <form method="POST" action="{{ route('admin.login.attempt') }}" class="login-card">
        @csrf
        <div class="login-card__brand">
            <span class="brand__mark brand__mark--lg"><x-icon name="briefcase-medical"/></span>
            <h1>{{ setting('clinic_name') }}</h1>
            <p>Website administration</p>
        </div>

        @if($errors->any())
            <div class="alert alert--danger"><x-icon name="alert-triangle" class="w-5 h-5"/> {{ $errors->first() }}</div>
        @endif

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>

        <label class="check-label">
            <input type="checkbox" name="remember"> Keep me signed in
        </label>

        <button type="submit" class="btn btn--primary btn--block btn--lg">Sign in</button>

        <a href="{{ route('home') }}" class="login-back"><x-icon name="arrow-right" class="w-4 h-4"/> Back to website</a>
    </form>
</div>
@endsection
