@extends('layouts.app')
@section('title','Dashboard | JobStock')
@section('content')
<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><div><span class="section-kicker">JOBSTOCK WORKSPACE</span><h1 class="h2 fw-bold mb-1">Welcome, {{ $user->name }}</h1><p class="text-secondary mb-0">Your {{ str_replace('_',' ',$user->role) }} dashboard.</p></div><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary">Sign out</button></form></div>
    <div class="row g-3">@foreach($stats as $label=>$value)<div class="col-6 col-lg-3"><div class="content-panel p-4 h-100"><div class="text-secondary small mb-2">{{ $label }}</div><div class="h3 fw-bold mb-0">{{ $value }}</div></div></div>@endforeach</div>
    <div class="content-panel p-4 mt-4"><h2 class="h5 fw-bold">Quick actions</h2><p class="text-secondary">Keep your information and activity up to date.</p>
        @if($user->role==='candidate')<div class="d-flex flex-wrap gap-2"><a class="btn btn-primary" href="{{ route('candidate.profile.edit') }}">Edit profile</a><a class="btn btn-outline-primary" href="{{ route('jobs.index') }}">Find jobs</a><a class="btn btn-outline-primary" href="{{ route('candidate.applications.index') }}">My applications</a><a class="btn btn-outline-primary" href="{{ route('candidate.saved-jobs.index') }}">Saved jobs</a></div>
        @elseif($user->role==='employer')<div class="d-flex flex-wrap gap-2"><a class="btn btn-primary" href="{{ route('employer.profile.edit') }}">Company profile</a><a class="btn btn-outline-primary" href="{{ route('employer.jobs.index') }}">Manage jobs</a><a class="btn btn-outline-primary" href="{{ route('employer.applications.index') }}">Review applicants</a></div>
        @else<p class="mb-0">Administrator access is enabled. Management modules are available from the navigation.</p>@endif
    </div>
</section>
@endsection
