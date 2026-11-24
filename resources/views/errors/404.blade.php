@extends('layouts.app')
@section('title', 'Page not found | JobStock')
@section('content')<section class="container py-5 text-center"><h1 class="display-1 fw-bold text-primary">404</h1><h2 class="h3">Page not found</h2><p class="text-secondary">The page you are looking for may have moved or is no longer available.</p><a class="btn btn-primary rounded-pill px-4" href="{{ route('home') }}">Return home</a></section>@endsection
