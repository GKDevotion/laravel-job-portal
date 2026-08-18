@extends('layouts.app')

@section('title', __('Find Jobs'))

@section('content')
<div class="flex gap-8">
    {{-- Filters --}}
    <aside class="w-64 shrink-0">
        <form method="GET" action="{{ route('jobs.index') }}" class="bg-white border rounded-xl p-4 space-y-4 text-sm">
            <div>
                <label class="block font-medium mb-1">{{ __('Keyword') }}</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('Job title, company...') }}"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block font-medium mb-1">{{ __('Category') }}</label>
                <select name="category" class="w-full border rounded-lg px-3 py-2">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category'] ?? null) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">{{ __('Job Type') }}</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected(($filters['type'] ?? null) == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">{{ __('Work Mode') }}</label>
                <select name="work_mode" class="w-full border rounded-lg px-3 py-2">
                    <option value="">{{ __('Any') }}</option>
                    @foreach (['onsite' => 'On-site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'] as $val => $label)
                        <option value="{{ $val }}" @selected(($filters['work_mode'] ?? null) === $val)>{{ __($label) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">
                {{ __('Apply Filters') }}
            </button>
        </form>
    </aside>

    {{-- Results --}}
    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">{{ __(':count jobs found', ['count' => $jobs->total()]) }}</p>
            <form method="GET" class="flex items-center gap-2 text-sm">
                @foreach ($filters as $key => $value)
                    @if ($key !== 'sort' && $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <label>{{ __('Sort:') }}</label>
                <select name="sort" onchange="this.form.submit()" class="border rounded-lg px-2 py-1">
                    <option value="" @selected(empty($filters['sort']))>{{ __('Newest') }}</option>
                    <option value="salary_high" @selected(($filters['sort'] ?? '') === 'salary_high')>{{ __('Salary: High to Low') }}</option>
                    <option value="salary_low" @selected(($filters['sort'] ?? '') === 'salary_low')>{{ __('Salary: Low to High') }}</option>
                </select>
            </form>
        </div>

        <div class="space-y-4">
            @forelse ($jobs as $job)
                <a href="{{ route('jobs.show', $job) }}" class="block bg-white border rounded-xl p-5 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-lg">
                                {{ $job->title }}
                                @if ($job->is_featured)
                                    <span class="ml-2 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ __('Featured') }}</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500">{{ $job->company->company_name }} &middot; {{ $job->city ?? $job->work_mode }}</p>
                        </div>
                        <span class="text-xs bg-gray-100 px-3 py-1 rounded-full">{{ $job->type->name }}</span>
                    </div>
                    @if ($job->salary_min || $job->salary_max)
                        <p class="text-sm text-gray-700 mt-3">
                            {{ money($job->salary_min) }} - {{ money($job->salary_max) }} / {{ $job->salary_period }}
                        </p>
                    @endif
                </a>
            @empty
                <p class="text-center text-gray-500 py-12">{{ __('No jobs match your filters yet.') }}</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $jobs->links() }}</div>
    </div>
</div>
@endsection
