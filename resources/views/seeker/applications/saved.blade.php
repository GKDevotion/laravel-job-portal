@extends('layouts.app')
@section('title', __('Saved Jobs'))
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Saved Jobs') }}</h1>
<div class="space-y-4">
    @forelse ($savedJobs as $job)
        <a href="{{ route('jobs.show', $job) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
            <p class="font-semibold">{{ $job->title }}</p>
            <p class="text-sm text-gray-500">{{ $job->company->company_name }}</p>
        </a>
    @empty
        <p class="text-gray-500 text-sm text-center py-8">{{ __("You haven't saved any jobs yet.") }}</p>
    @endforelse
</div>
<div class="mt-4">{{ $savedJobs->links() }}</div>
@endsection
