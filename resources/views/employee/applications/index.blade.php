@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Applicants') }}</h1>
<form method="GET" class="flex gap-3 mb-6 text-sm">
    <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
        <option value="">{{ __('All Statuses') }}</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</form>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Applicant') }}</th><th class="px-4 py-3">{{ __('Job') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($applications as $application)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $application->user->name }}</td>
                    <td class="px-4 py-3">{{ $application->jobListing->title }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ ucfirst($application->status) }}</span></td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('employee.applications.show', $application) }}" class="text-indigo-600 hover:underline">{{ __('Review') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('No applicants yet.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $applications->links() }}</div>
@endsection
