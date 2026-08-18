@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Welcome back, :name', ['name' => $user->name]) }}</h1>
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $applicationsCount }}</p><p class="text-sm text-gray-500">{{ __('Applications') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $savedJobsCount }}</p><p class="text-sm text-gray-500">{{ __('Saved Jobs') }}</p></div>
    <a href="{{ route('jobs.index') }}" class="bg-indigo-600 text-white rounded-xl p-5 flex items-center justify-center hover:bg-indigo-700">{{ __('Browse Jobs') }}</a>
</div>
<h2 class="font-semibold mb-3">{{ __('Recent Applications') }}</h2>
<div class="bg-white border rounded-xl divide-y">
    @forelse ($recentApplications as $app)
        <div class="p-4 flex justify-between text-sm">
            <div>
                <p class="font-medium">{{ $app->jobListing->title }}</p>
                <p class="text-gray-500">{{ $app->jobListing->company->company_name }}</p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 h-fit">{{ ucfirst($app->status) }}</span>
        </div>
    @empty
        <p class="p-4 text-gray-500 text-sm">{{ __("You haven't applied to any jobs yet.") }}</p>
    @endforelse
</div>
@endsection
