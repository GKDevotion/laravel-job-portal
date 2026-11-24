@extends('layouts.auth')
@section('title', 'Sign in | JobStock')
@section('content')
<section class="auth-card">
    <div class="text-center">
        <span class="auth-kicker">JobStock account</span>
        <h1>Welcome back</h1>
        <p class="auth-intro">Sign in to continue to your JobStock account.</p>
    </div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">@csrf
    <div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"></div>
    <div><label class="form-label" for="password">Password</label><input class="form-control" id="password" name="password" type="password" required autocomplete="current-password"></div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"><label class="form-check mb-0"><input class="form-check-input" type="checkbox" name="remember" value="1"><span class="form-check-label">Remember me</span></label><a href="{{ route('password.request') }}">Forgot password?</a></div>
    <button class="btn btn-primary auth-submit w-100" type="submit">Sign in</button>
</form>
    <p class="auth-switch">New to JobStock? <a href="{{ route('register') }}">Create candidate account</a> <span class="mx-1">·</span> <a href="{{ route('employer.register') }}">Register as employer</a></p>
</section>
@endsection
