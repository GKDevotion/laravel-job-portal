@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Edit Job') }}</h1>
<form method="POST" action="{{ route('employee.jobs.update', $job) }}" class="space-y-5 bg-white border rounded-xl p-6">
    @csrf @method('PUT')
    @include('company.jobs._form', ['job' => $job])
    <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Save Changes') }}</button>
</form>
@if (in_array($job->status, ['draft', 'rejected']))
    <form method="POST" action="{{ route('employee.jobs.submit-review', $job) }}" class="mt-4">
        @csrf
        <button class="border border-indigo-600 text-indigo-600 rounded-lg px-5 py-2 hover:bg-indigo-50">{{ __('Submit for Review') }}</button>
    </form>
@endif
@endsection
