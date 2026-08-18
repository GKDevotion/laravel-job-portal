@extends('layouts.dashboard')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold">{{ __('Edit Job') }}</h1>
    <span class="text-xs px-3 py-1 rounded-full
        @class([
            'bg-gray-100 text-gray-700' => $job->status === 'draft',
            'bg-amber-100 text-amber-700' => $job->status === 'pending_review',
            'bg-green-100 text-green-700' => $job->status === 'published',
            'bg-red-100 text-red-700' => in_array($job->status, ['closed', 'rejected', 'expired']),
        ])">
        {{ str_replace('_', ' ', ucfirst($job->status)) }}
    </span>
</div>

@if ($job->status === 'rejected' && $job->rejection_reason)
    <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-6">
        <strong>{{ __('Rejection reason:') }}</strong> {{ $job->rejection_reason }}
    </div>
@endif

<form method="POST" action="{{ route('company.jobs.update', $job) }}" class="space-y-5 bg-white border rounded-xl p-6">
    @csrf
    @method('PUT')
    @include('company.jobs._form', ['job' => $job])
    <div class="flex gap-3">
        <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Save Changes') }}</button>
    </div>
</form>

<div class="flex gap-3 mt-4">
    @if (in_array($job->status, ['draft', 'rejected']))
        <form method="POST" action="{{ route('company.jobs.submit-review', $job) }}">
            @csrf
            <button class="border border-indigo-600 text-indigo-600 rounded-lg px-5 py-2 hover:bg-indigo-50">
                {{ __('Submit for Review') }}
            </button>
        </form>
    @endif

    @if ($job->status === 'published')
        <form method="POST" action="{{ route('company.jobs.close', $job) }}">
            @csrf
            <button class="border border-gray-400 text-gray-600 rounded-lg px-5 py-2 hover:bg-gray-50">
                {{ __('Close Job') }}
            </button>
        </form>
    @endif

    <form method="POST" action="{{ route('company.jobs.destroy', $job) }}" onsubmit="return confirm('{{ __('Delete this job permanently?') }}')">
        @csrf
        @method('DELETE')
        <button class="border border-red-400 text-red-600 rounded-lg px-5 py-2 hover:bg-red-50">{{ __('Delete') }}</button>
    </form>
</div>
@endsection
