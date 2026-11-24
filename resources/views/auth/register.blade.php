@extends('layouts.auth')
@section('title', ($accountType === 'employer' ? 'Employer registration' : 'Candidate registration').' | JobStock')
@section('content')
<section class="auth-card auth-card--register {{ $accountType === 'employer' ? 'auth-card--employer' : 'auth-card--candidate' }}">
    <div class="text-center"><span class="auth-kicker">Join JobStock</span>
        <h1>{{ $accountType === 'employer' ? 'Create employer account' : 'Create candidate account' }}</h1>
        <p class="auth-intro">{{ $accountType === 'employer' ? 'Find talent and manage your hiring.' : 'Create your profile and discover opportunities.' }}</p></div>
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ $accountType === 'employer' ? route('employer.register.store') : route('register.store') }}" class="vstack gap-3">@csrf
    <div><label class="form-label" for="name">Full name</label><input class="form-control" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"></div>
    @if($accountType === 'employer')<div><label class="form-label" for="company_name">Company name</label><input class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required></div>@endif
    <div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"></div>
    <div><label class="form-label" for="password">Password</label><input class="form-control" id="password" name="password" type="password" required autocomplete="new-password" minlength="8"></div>
    <div><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"></div>
    <label class="form-check"><input class="form-check-input" type="checkbox" name="terms" value="1" required><span class="form-check-label">I agree to the terms and privacy policy.</span></label>
    <button class="btn btn-primary auth-submit w-100" type="submit">Create account</button>
</form>
    <p class="auth-switch">Already registered? <a href="{{ route('login') }}">Sign in</a></p>
</section>
@endsection
