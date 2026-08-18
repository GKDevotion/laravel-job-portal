@extends('layouts.app')
@section('title', __('Find Your Next Job'))
@section('content')
<div class="text-center py-12">
    <h1 class="text-3xl font-bold">{{ __('Find Your Next Job') }}</h1>
    <p class="text-gray-500 mt-2">{{ __('Search thousands of jobs from companies around the world.') }}</p>
    <form method="GET" action="{{ route('jobs.index') }}" class="max-w-xl mx-auto mt-6 flex gap-2">
        <input type="text" name="q" placeholder="{{ __('Job title, keywords...') }}" class="flex-1 border rounded-lg px-4 py-3">
        <button type="submit" class="bg-indigo-600 text-white rounded-lg px-6 py-3 hover:bg-indigo-700">{{ __('Search') }}</button>
    </form>
</div>

@if ($categories->isNotEmpty())
    <div class="grid grid-cols-4 gap-4 mb-12">
        @foreach ($categories as $category)
            <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="bg-white border rounded-xl p-4 text-center hover:shadow-md transition">
                <p class="font-medium">{{ $category->name }}</p>
                <p class="text-xs text-gray-500">{{ __(':count jobs', ['count' => $category->job_listings_count]) }}</p>
            </a>
        @endforeach
    </div>
@endif

@if ($featuredJobs->isNotEmpty())
    <h2 class="text-lg font-semibold mb-4">{{ __('Featured Jobs') }}</h2>
    <div class="grid grid-cols-2 gap-4 mb-12">
        @foreach ($featuredJobs as $job)
            <a href="{{ route('jobs.show', $job) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
                <p class="font-semibold">{{ $job->title }}</p>
                <p class="text-sm text-gray-500">{{ $job->company->company_name }} &middot; {{ $job->type->name }}</p>
            </a>
        @endforeach
    </div>
@endif

<h2 class="text-lg font-semibold mb-4">{{ __('Latest Jobs') }}</h2>
<div class="space-y-4">
    @foreach ($latestJobs as $job)
        <a href="{{ route('jobs.show', $job) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
            <p class="font-semibold">{{ $job->title }}</p>
            <p class="text-sm text-gray-500">{{ $job->company->company_name }} &middot; {{ $job->type->name }}</p>
        </a>
    @endforeach
</div>
@endsection
