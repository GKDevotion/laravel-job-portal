@extends('layouts.app')
@section('title','My Applications | JobStock')
@section('content')
<section class="container py-5">
    <div class="mb-4"><span class="section-kicker">CANDIDATE WORKSPACE</span><h1 class="h2 fw-bold">My Applications</h1><p class="text-secondary mb-0">Track the progress of your job applications.</p></div>
    @forelse($applications as $application)
        <article class="content-panel p-4 mb-3"><div class="d-flex flex-wrap justify-content-between gap-3"><div><h2 class="h5 fw-bold mb-1"><a href="{{ route('jobs.show',$application->job->slug) }}">{{ $application->job->title }}</a></h2><p class="text-secondary mb-2">{{ $application->job->company?->name }} · {{ $application->job->location }}</p><span class="badge text-bg-light border">{{ ucfirst($application->status) }}</span></div><div class="text-secondary small">Applied {{ $application->created_at->format('d M Y') }}</div></div>
            @if($application->status === 'submitted')<form method="POST" action="{{ route('candidate.applications.withdraw',$application) }}" class="mt-3" onsubmit="return confirm('Withdraw this application?')">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger">Withdraw application</button></form>@endif
        </article>
    @empty <div class="content-panel p-5 text-center"><h2 class="h5">No applications yet</h2><p class="text-secondary">Explore open roles and submit your first application.</p><a class="btn btn-primary" href="{{ route('jobs.index') }}">Find jobs</a></div> @endforelse
    {{ $applications->links() }}
</section>
@endsection
