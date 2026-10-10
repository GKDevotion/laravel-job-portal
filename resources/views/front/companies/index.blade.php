@extends('layouts.app')
@section('title', __('Companies'))
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Companies Hiring Now') }}</h1>
<form method="GET" class="mb-6"><input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search companies...') }}" class="border rounded-lg px-4 py-2 w-full max-w-md"></form>
<div class="grid grid-cols-3 gap-4">
    @forelse ($companies as $company)
        <a href="{{ route('companies.show', $company) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
            <div class="flex items-center gap-3 mb-2">
                @if ($logoUrl = $company->logoUrl())
                    <img src="{{ $logoUrl }}" class="w-10 h-10 rounded-lg object-cover shrink-0" alt="{{ $company->company_name }}">
                @else
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 font-semibold shrink-0">{{ substr($company->company_name, 0, 1) }}</div>
                @endif
                <p class="font-semibold">{{ $company->company_name }}</p>
            </div>
            <p class="text-sm text-gray-500">{{ $company->industry }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ __(':count open jobs', ['count' => $company->job_listings_count]) }}</p>
        </a>
    @empty
        <p class="text-gray-500 col-span-3 text-center py-8">{{ __('No companies found.') }}</p>
    @endforelse
</div>
<div class="mt-6">{{ $companies->links() }}</div>
@endsection
