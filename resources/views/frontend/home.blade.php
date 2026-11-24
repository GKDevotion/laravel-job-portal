@extends('layouts.app')
@section('title', 'Find Your Next Opportunity | JobStock')
@section('content')
<section class="jobstock-hero">
    <div class="container position-relative py-5 py-xl-6">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-eyebrow"><span></span> Get hot &amp; trending jobs</span>
                <h1 class="display-4 fw-bold mt-3 mb-3">Real Jobs. Real People. <span>Real Success.</span></h1>
                <p class="lead text-secondary mb-4">Find the role that fits your ambition. Discover opportunities from companies ready to meet their next great teammate.</p>
                <div class="d-flex flex-wrap gap-4 hero-stats"><div><strong>{{ number_format($jobCount) }}+</strong><small>Active jobs</small></div><div><strong>{{ number_format($companyCount) }}+</strong><small>Companies</small></div><div><strong>100%</strong><small>Career focused</small></div></div>
            </div>
            <div class="col-lg-6">
                <div class="hero-search-card">
                    <span class="badge rounded-pill text-bg-success-subtle text-success mb-3">Your next chapter starts here</span>
                    <h2 class="h3 fw-bold mb-2">Grow your career with JobStock</h2><p class="text-secondary mb-4">Search thousands of opportunities in one place.</p>
                    <form action="{{ route('jobs.index') }}" method="GET" class="row g-3">
                        <div class="col-12"><label class="form-label small fw-semibold" for="keyword">Job title or keyword</label><div class="input-group input-group-lg"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input class="form-control" id="keyword" name="keyword" placeholder="e.g. Product Designer, Developer"></div></div>
                        <div class="col-md-7"><label class="form-label small fw-semibold" for="location">Location</label><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span><input class="form-control" id="location" name="location" placeholder="City or remote"></div></div>
                        <div class="col-md-5 d-flex align-items-end"><button class="btn btn-primary btn-lg w-100" type="submit">Find Jobs <i class="bi bi-arrow-right ms-1"></i></button></div>
                    </form>
                    <p class="small text-secondary mt-3 mb-0">Popular: <a href="{{ route('jobs.index', ['keyword' => 'Developer']) }}">Developer</a>, <a href="{{ route('jobs.index', ['keyword' => 'Designer']) }}">Designer</a>, <a href="{{ route('jobs.index', ['keyword' => 'Marketing']) }}">Marketing</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5"><div class="container">
    <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4"><div><span class="section-kicker">Explore by category</span><h2 class="h2 fw-bold mb-1">Find work in your field</h2><p class="text-secondary mb-0">Browse opportunities across popular industries.</p></div><a class="btn btn-outline-primary rounded-pill" href="{{ route('jobs.index') }}">All categories <i class="bi bi-arrow-up-right ms-1"></i></a></div>
    <div class="row g-3">@forelse($categories as $category)<div class="col-6 col-md-4 col-xl-3"><a class="category-card h-100" href="{{ route('jobs.index', ['category' => $category->slug]) }}"><span class="category-icon"><i class="{{ $category->icon ?: 'bi bi-briefcase' }}"></i></span><span class="fw-semibold">{{ $category->name }}</span><small>{{ $category->jobs_count }} open jobs</small></a></div>@empty<div class="col-12"><p class="text-secondary">Job categories will appear here once added by the administrator.</p></div>@endforelse</div>
</div></section>
<section class="py-5 bg-light-subtle"><div class="container">
    <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4"><div><span class="section-kicker">Handpicked for you</span><h2 class="h2 fw-bold mb-1">Featured jobs</h2><p class="text-secondary mb-0">A selection of roles worth taking a closer look at.</p></div><a class="btn btn-outline-primary rounded-pill" href="{{ route('jobs.index') }}">Explore all jobs <i class="bi bi-arrow-right ms-1"></i></a></div>
    <div class="row g-3">@forelse($featuredJobs as $job)<div class="col-md-6 col-xl-4"><article class="job-card h-100"><div class="d-flex align-items-start gap-3"><div class="company-mark">{{ mb_substr($job->company?->name ?? 'J', 0, 1) }}</div><div class="flex-grow-1"><h3 class="h6 fw-bold mb-1"><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3><p class="small text-secondary mb-0">{{ $job->company?->name }}</p></div><span class="featured-dot" title="Featured"></span></div><div class="d-flex flex-wrap gap-2 my-3"><span class="job-chip"><i class="bi bi-geo-alt"></i> {{ $job->location ?: 'Location flexible' }}</span><span class="job-chip">{{ $job->job_type }}</span></div><div class="d-flex justify-content-between align-items-center border-top pt-3"><span class="small text-secondary">{{ $job->category?->name ?? 'General' }}</span><a class="fw-semibold small" href="{{ route('jobs.show', $job->slug) }}">View details <i class="bi bi-arrow-up-right"></i></a></div></article></div>@empty<div class="col-12"><div class="empty-state">No featured jobs have been published yet.</div></div>@endforelse</div>
</div></section>
<section class="py-5"><div class="container">
    <div class="section-heading mb-4"><span class="section-kicker">Recently published</span><h2 class="h2 fw-bold mb-1">Latest opportunities</h2><p class="text-secondary mb-0">Fresh openings from our employer community.</p></div>
    <div class="latest-jobs-list">@forelse($latestJobs as $job)<a class="latest-job-row" href="{{ route('jobs.show', $job->slug) }}"><div class="company-mark">{{ mb_substr($job->company?->name ?? 'J', 0, 1) }}</div><div class="flex-grow-1"><h3 class="h6 fw-bold mb-1">{{ $job->title }}</h3><p class="small text-secondary mb-0">{{ $job->company?->name }} · {{ $job->location ?: 'Flexible location' }}</p></div><span class="job-chip d-none d-sm-inline-flex">{{ $job->job_type }}</span><i class="bi bi-arrow-up-right ms-2"></i></a>@empty<div class="empty-state">No jobs published yet. Check back soon.</div>@endforelse</div>
</div></section>
<section class="py-5" id="how-it-works"><div class="container"><div class="cta-panel text-center"><span class="section-kicker">Make your next move</span><h2 class="display-6 fw-bold mt-2">Your next opportunity is closer than you think.</h2><p class="text-secondary mx-auto">Create your candidate profile and start exploring opportunities that match your skills and goals.</p><a class="btn btn-primary btn-lg rounded-pill px-4" href="{{ route('register') }}">Create a free account <i class="bi bi-arrow-right ms-1"></i></a></div></div></section>
@endsection
