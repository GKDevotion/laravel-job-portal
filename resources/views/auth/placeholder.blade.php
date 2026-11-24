@extends('layouts.app')
@section('title', $heading.' | JobStock')
@section('content')<section class="container py-5"><div class="content-panel mx-auto text-center" style="max-width:620px"><span class="section-kicker">JobStock account</span><h1 class="h2 fw-bold">{{ $heading }}</h1><p class="text-secondary">This screen is reserved for the authentication workflow in the next development batch.</p><a class="btn btn-primary" href="{{ route('home') }}">Back to home</a></div></section>@endsection
