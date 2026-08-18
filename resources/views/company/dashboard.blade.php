@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Welcome, :name', ['name' => $company->company_name]) }}</h1>

@unless ($company->isApproved())
    <div class="bg-amber-50 text-amber-700 border border-amber-200 rounded-lg px-4 py-3 text-sm mb-6">
        {{ __('Your company is pending admin approval. You can prepare job drafts now, but publishing is disabled until approved.') }}
    </div>
@endunless

<div class="grid grid-cols-2 gap-4 mb-8">
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $activeJobsCount }}</p><p class="text-sm text-gray-500">{{ __('Active Job Listings') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $totalApplications }}</p><p class="text-sm text-gray-500">{{ __('Total Applicants') }}</p></div>
</div>

<h2 class="font-semibold mb-3">{{ __('Recent Applicants') }}</h2>
<div class="bg-white border rounded-xl divide-y">
    @forelse ($recentApplications as $app)
        <a href="{{ route('company.applications.show', $app) }}" class="p-4 flex justify-between text-sm hover:bg-gray-50">
            <div>
                <p class="font-medium">{{ $app->user->name }}</p>
                <p class="text-gray-500">{{ $app->jobListing->title }}</p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 h-fit">{{ ucfirst($app->status) }}</span>
        </a>
    @empty
        <p class="p-4 text-gray-500 text-sm">{{ __('No applicants yet.') }}</p>
    @endforelse
</div>
@endsection
