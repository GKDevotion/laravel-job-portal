@extends('layouts.admin')
@section('content')
<a href="{{ route('admin.companies.index') }}" class="text-sm text-indigo-600">&larr; {{ __('Back to Companies') }}</a>

<div class="grid grid-cols-3 gap-6 mt-4">
    <div class="col-span-2 bg-white border rounded-xl p-6">
        <h1 class="text-xl font-bold">{{ $company->company_name }}</h1>
        <p class="text-sm text-gray-500">{{ $company->email }} @if($company->phone) &middot; {{ $company->phone }} @endif</p>
        @if ($company->about)
            <p class="text-sm text-gray-700 mt-4">{{ $company->about }}</p>
        @endif
        <dl class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div><dt class="text-gray-500">{{ __('Industry') }}</dt><dd>{{ $company->industry ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">{{ __('Team Size') }}</dt><dd>{{ $company->team_size ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">{{ __('Country') }}</dt><dd>{{ $company->country ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">{{ __('Job Credits') }}</dt><dd>{{ $company->hasUnlimitedCredits() ? __('Unlimited') : $company->job_posting_credits }}</dd></div>
        </dl>

        <h2 class="font-medium mt-6 mb-2">{{ __('Recent Jobs') }}</h2>
        <div class="divide-y border-t">
            @forelse ($company->jobListings as $job)
                <div class="py-2 flex justify-between text-sm">
                    <span>{{ $job->title }}</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ str_replace('_', ' ', ucfirst($job->status)) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-500 py-2">{{ __('No jobs posted yet.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white border rounded-xl p-6">
            <h2 class="font-medium mb-3">{{ __('Approval') }}</h2>
            <span class="text-xs px-2 py-1 rounded-full
                @class(['bg-amber-100 text-amber-700' => $company->approval_status === 'pending', 'bg-green-100 text-green-700' => $company->approval_status === 'approved', 'bg-red-100 text-red-700' => $company->approval_status === 'rejected'])">
                {{ ucfirst($company->approval_status) }}
            </span>

            @if ($company->approval_status !== 'approved')
                <form method="POST" action="{{ route('admin.companies.approve', $company) }}" class="mt-4">
                    @csrf
                    <button class="w-full bg-green-600 text-white rounded-lg py-2 text-sm hover:bg-green-700">{{ __('Approve') }}</button>
                </form>
            @endif

            @if ($company->approval_status !== 'rejected')
                <form method="POST" action="{{ route('admin.companies.reject', $company) }}" class="mt-3 space-y-2">
                    @csrf
                    <textarea name="rejection_reason" rows="2" placeholder="{{ __('Reason for rejection...') }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                    <button class="w-full border border-red-400 text-red-600 rounded-lg py-2 text-sm hover:bg-red-50">{{ __('Reject') }}</button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.companies.toggle-status', $company) }}" class="mt-3">
                @csrf @method('PATCH')
                <button class="w-full border rounded-lg py-2 text-sm hover:bg-gray-50">{{ $company->status ? __('Suspend Account') : __('Reactivate Account') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
