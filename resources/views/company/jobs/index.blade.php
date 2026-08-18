@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold">{{ __('Your Job Listings') }}</h1>
    <a href="{{ route('company.jobs.create') }}" class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-indigo-700">
        + {{ __('Post a Job') }}
    </a>
</div>

<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-3">{{ __('Title') }}</th>
                <th class="px-4 py-3">{{ __('Status') }}</th>
                <th class="px-4 py-3">{{ __('Applicants') }}</th>
                <th class="px-4 py-3">{{ __('Posted') }}</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($jobs as $job)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $job->title }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ str_replace('_', ' ', ucfirst($job->status)) }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $job->applications_count }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $job->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('company.jobs.edit', $job) }}" class="text-indigo-600 hover:underline">{{ __('Edit') }}</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __("You haven't posted any jobs yet.") }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $jobs->links() }}</div>
@endsection
