@extends('layouts.auth')
@section('title', 'Verify email | JobStock')
@section('content')
<section class="auth-card">
    <div class="text-center">
        <span class="auth-kicker">One last step</span>
        <h1>Verify your email address</h1>
        <p class="auth-intro">We sent a verification link to your registered email address. Verify it to access your dashboard.</p>
    </div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="d-grid gap-2">
<form method="POST" action="{{ route('verification.send') }}">@csrf<button class="btn btn-primary auth-submit w-100" type="submit">Resend verification email</button></form>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary w-100" type="submit">Sign out</button></form>
</div>
    
</section>
@endsection
