@extends('layouts.auth')
@section('title', 'Forgot password | JobStock')
@section('content')
<section class="auth-card">
    <div class="text-center">
        <span class="auth-kicker">Account recovery</span>
        <h1>Reset your password</h1>
        <p class="auth-intro">Enter your email address and we'll send you a secure reset link.</p>
    </div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">@csrf
<div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></div>
<button class="btn btn-primary auth-submit w-100" type="submit">Send reset link</button></form>
    <p class="auth-switch"><a href="{{ route('login') }}">Back to sign in</a></p>
</section>
@endsection
