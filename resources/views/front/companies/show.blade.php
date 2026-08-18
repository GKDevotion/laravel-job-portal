@extends('layouts.app')
@section('title', $company->company_name)
@section('content')
<div class="bg-white border rounded-xl p-6 mb-8">
    <h1 class="text-2xl font-bold">{{ $company->company_name }}</h1>
    <p class="text-gray-500">{{ $company->industry }} @if($company->city) &middot; {{ $company->city }}, {{ $company->country }} @endif</p>
    @if ($company->about)
        <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $company->about }}</p>
    @endif
</div>
<h2 class="text-lg font-semibold mb-4">{{ __('Open Positions') }}</h2>
<div class="space-y-4">
    @forelse ($jobs as $job)
        <a href="{{ route('jobs.show', $job) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
            <p class="font-semibold">{{ $job->title }}</p>
            <p class="text-sm text-gray-500">{{ $job->type->name }} &middot; {{ ucfirst($job->work_mode) }}</p>
        </a>
    @empty
        <p class="text-gray-500 text-center py-8">{{ __('No open positions right now.') }}</p>
    @endforelse
</div>
<div class="mt-6">{{ $jobs->links() }}</div>
@endsection
