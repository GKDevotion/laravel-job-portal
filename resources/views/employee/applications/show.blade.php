@extends('layouts.dashboard')
@section('content')
<a href="{{ route('employee.applications.index') }}" class="text-sm text-indigo-600">&larr; {{ __('Back to Applicants') }}</a>
<div class="grid grid-cols-3 gap-6 mt-4">
    <div class="col-span-2 bg-white border rounded-xl p-6">
        <h1 class="text-xl font-bold">{{ $application->user->name }}</h1>
        <p class="text-gray-500 text-sm">{{ $application->user->email }}</p>
        <p class="text-sm mt-2">{{ __('Applied for') }}: <strong>{{ $application->jobListing->title }}</strong></p>
        @if ($application->cover_letter)
            <div class="mt-4 pt-4 border-t">
                <h2 class="font-medium mb-2">{{ __('Cover Letter') }}</h2>
                <p class="text-sm whitespace-pre-line text-gray-700">{{ $application->cover_letter }}</p>
            </div>
        @endif
        <div class="mt-4 pt-4 border-t">
            <a href="{{ \Illuminate\Support\Facades\Storage::url($application->resume_path) }}" target="_blank" class="text-indigo-600 text-sm hover:underline">{{ __('Download Resume') }} &rarr;</a>
        </div>
    </div>
    <div class="bg-white border rounded-xl p-6">
        <h2 class="font-medium mb-3">{{ __('Application Status') }}</h2>
        <form method="POST" action="{{ route('employee.applications.status', $application) }}" class="space-y-3">
            @csrf @method('PATCH')
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach (['submitted', 'viewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected'] as $status)
                    <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 text-sm hover:bg-indigo-700">{{ __('Update Status') }}</button>
        </form>
    </div>
</div>
@endsection
