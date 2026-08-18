@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Welcome, :name', ['name' => $employee->name]) }}</h1>
<div class="grid grid-cols-2 gap-4">
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $myPostedJobsCount }}</p><p class="text-sm text-gray-500">{{ __('Jobs You Posted') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $pendingReviewCount }}</p><p class="text-sm text-gray-500">{{ __('Applicants Awaiting Review') }}</p></div>
</div>
@endsection
