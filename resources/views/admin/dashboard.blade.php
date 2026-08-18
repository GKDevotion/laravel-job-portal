@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Admin Dashboard') }}</h1>
<div class="grid grid-cols-4 gap-4">
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $companiesCount }}</p><p class="text-sm text-gray-500">{{ __('Companies') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $pendingCompaniesCount }}</p><p class="text-sm text-gray-500">{{ __('Pending Approval') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $usersCount }}</p><p class="text-sm text-gray-500">{{ __('Job Seekers') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $jobsCount }}</p><p class="text-sm text-gray-500">{{ __('Total Jobs') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $pendingJobsCount }}</p><p class="text-sm text-gray-500">{{ __('Jobs Awaiting Review') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ $unpaidInvoicesCount }}</p><p class="text-sm text-gray-500">{{ __('Unpaid Invoices') }}</p></div>
    <div class="bg-white border rounded-xl p-5"><p class="text-2xl font-bold">{{ number_format($revenueThisMonth, 2) }}</p><p class="text-sm text-gray-500">{{ __('Revenue This Month') }}</p></div>
</div>
@endsection
