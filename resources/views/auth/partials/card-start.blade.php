@extends('layouts.app')
@section('content')
<section class="container py-5"><div class="row justify-content-center"><div class="col-12 col-md-9 col-lg-6 col-xl-5"><div class="content-panel p-4 p-md-5 shadow-sm"><div class="text-center mb-4"><span class="section-kicker">JOBSTOCK ACCOUNT</span><h1 class="h2 fw-bold mb-2">@yield('form_title')</h1><p class="text-secondary mb-0">@yield('form_subtitle')</p></div>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
