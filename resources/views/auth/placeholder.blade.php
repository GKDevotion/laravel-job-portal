@extends('layouts.auth')
@section('title', $heading.' | JobStock')
@section('content')
<section class="auth-card text-center"><span class="auth-kicker">JobStock account</span><h1>{{ $heading }}</h1><p class="auth-intro">This account screen is being prepared.</p><a class="btn btn-primary auth-submit w-100 d-flex align-items-center justify-content-center" href="{{ route('home') }}">Back to home</a></section>
@endsection
