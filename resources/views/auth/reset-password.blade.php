@extends('layouts.auth')
@section('title', 'Choose new password | JobStock')
@section('content')
<section class="auth-card">
    <div class="text-center">
        <span class="auth-kicker">Secure your account</span>
        <h1>Choose a new password</h1>
        <p class="auth-intro">Use at least eight characters for your new password.</p>
    </div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('password.store') }}" class="vstack gap-3">@csrf
<input type="hidden" name="token" value="{{ $token }}">
<div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autocomplete="email"></div>
<div><label class="form-label" for="password">New password</label><input class="form-control" id="password" name="password" type="password" required minlength="8" autocomplete="new-password"></div>
<div><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"></div>
<button class="btn btn-primary auth-submit w-100" type="submit">Update password</button></form>
    <p class="auth-switch"><a href="{{ route('login') }}">Back to sign in</a></p>
</section>
@endsection
