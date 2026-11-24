@extends('layouts.app')
@section('title','Saved Jobs | JobStock')
@section('content')
<section class="container py-5"><div class="mb-4"><span class="section-kicker">CANDIDATE WORKSPACE</span><h1 class="h2 fw-bold">Saved Jobs</h1><p class="text-secondary">Roles you bookmarked for later.</p></div>
    @forelse($jobs as $job)<article class="content-panel p-4 mb-3"><div class="d-flex flex-wrap justify-content-between gap-3"><div><h2 class="h5 fw-bold mb-1"><a href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h2><p class="text-secondary mb-0">{{ $job->company?->name }} · {{ $job->location }}</p></div><div class="d-flex gap-2 align-items-center"><a class="btn btn-primary btn-sm" href="{{ route('jobs.show',$job->slug) }}">View job</a><form method="POST" action="{{ route('candidate.saved-jobs.toggle',$job) }}">@csrf @method('DELETE')<button class="btn btn-outline-secondary btn-sm">Remove</button></form></div></div></article>@empty<div class="content-panel p-5 text-center"><h2 class="h5">No saved jobs</h2><p class="text-secondary">Save interesting roles and find them here later.</p><a class="btn btn-primary" href="{{ route('jobs.index') }}">Explore jobs</a></div>@endforelse
    {{ $jobs->links() }}
</section>
@endsection
