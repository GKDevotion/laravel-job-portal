@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-2">{{ __('Buy Credits or Boost a Job') }}</h1>
<p class="text-sm text-gray-500 mb-6">{{ __('No live payment gateway yet — purchasing creates an invoice with payment instructions. An admin confirms payment manually.') }}</p>

<div class="grid grid-cols-3 gap-4">
    @forelse ($plans as $plan)
        <div class="bg-white border rounded-xl p-6">
            <p class="font-semibold text-lg">{{ $plan->name }}</p>
            @if ($plan->description)
                <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
            @endif
            <p class="text-2xl font-bold mt-4">{{ money($plan->price) }}</p>

            @if ($plan->type === 'job_credits')
                <p class="text-xs text-gray-500 mt-1">{{ __(':count job posting credits', ['count' => $plan->credits]) }}</p>
                <form method="POST" action="{{ route('company.pricing.purchase', $plan) }}" class="mt-4">
                    @csrf
                    <button class="w-full bg-indigo-600 text-white rounded-lg py-2 text-sm hover:bg-indigo-700">{{ __('Purchase') }}</button>
                </form>
            @else
                <p class="text-xs text-gray-500 mt-1">{{ __('Featured for :days days', ['days' => $plan->featured_days]) }}</p>
                <form method="POST" action="{{ route('company.pricing.purchase', $plan) }}" class="mt-4 space-y-2">
                    @csrf
                    <select name="job_listing_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">{{ __('Select a job to feature') }}</option>
                        @foreach ($myJobs as $job)
                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                        @endforeach
                    </select>
                    <button class="w-full bg-indigo-600 text-white rounded-lg py-2 text-sm hover:bg-indigo-700">{{ __('Purchase') }}</button>
                </form>
            @endif
        </div>
    @empty
        <p class="col-span-3 text-center text-gray-500 py-8">{{ __('No plans available yet.') }}</p>
    @endforelse
</div>
@endsection
