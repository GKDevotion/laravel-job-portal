@extends('layouts.admin')
@section('content')
<a href="{{ route('admin.jobs.index') }}" class="text-sm text-indigo-600">&larr; {{ __('Back to Job Moderation') }}</a>

<div class="bg-white border rounded-xl p-6 mt-4">
    <h1 class="text-xl font-bold">{{ $job->title }}</h1>
    <p class="text-sm text-gray-500">{{ $job->company->company_name }} &middot; {{ $job->category->name }} &middot; {{ $job->type->name }}</p>

    <div class="prose max-w-none mt-6 text-sm">
        <h2 class="font-medium">{{ __('Description') }}</h2>
        <p class="whitespace-pre-line">{{ $job->description }}</p>
        @if ($job->requirements)
            <h2 class="font-medium mt-4">{{ __('Requirements') }}</h2>
            <p class="whitespace-pre-line">{{ $job->requirements }}</p>
        @endif
    </div>

    @if ($job->status === 'pending_review')
        <div class="flex gap-3 mt-6 pt-6 border-t">
            <form method="POST" action="{{ route('admin.jobs.approve', $job) }}">
                @csrf
                <button class="bg-green-600 text-white rounded-lg px-5 py-2 text-sm hover:bg-green-700">{{ __('Approve & Publish') }}</button>
            </form>
            <form method="POST" action="{{ route('admin.jobs.reject', $job) }}" class="flex gap-2">
                @csrf
                <input type="text" name="rejection_reason" placeholder="{{ __('Reason for rejection...') }}" required class="border rounded-lg px-3 py-2 text-sm">
                <button class="border border-red-400 text-red-600 rounded-lg px-5 py-2 text-sm hover:bg-red-50">{{ __('Reject') }}</button>
            </form>
        </div>
    @else
        <p class="mt-6 pt-6 border-t text-sm text-gray-500">
            {{ __('Status') }}: <span class="font-medium">{{ str_replace('_', ' ', ucfirst($job->status)) }}</span>
        </p>
    @endif
</div>
@endsection
