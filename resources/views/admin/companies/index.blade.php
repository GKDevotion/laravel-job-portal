@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Companies') }}</h1>
<form method="GET" class="flex gap-3 mb-6 text-sm">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search companies...') }}" class="border rounded-lg px-3 py-2 flex-1 max-w-xs">
    <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
        <option value="">{{ __('All Statuses') }}</option>
        @foreach (['pending', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button class="border rounded-lg px-4 py-2">{{ __('Search') }}</button>
</form>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Company') }}</th><th class="px-4 py-3">{{ __('Jobs') }}</th><th class="px-4 py-3">{{ __('Approval') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($companies as $company)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if ($logoUrl = $company->logoUrl())
                                <img src="{{ $logoUrl }}" class="w-7 h-7 rounded object-cover" alt="{{ $company->company_name }}">
                            @else
                                <div class="w-7 h-7 rounded bg-gray-100 flex items-center justify-center text-[10px] text-gray-400">{{ substr($company->company_name, 0, 1) }}</div>
                            @endif
                            <div>
                                <p class="font-medium">{{ $company->company_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $company->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $company->job_listings_count }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full
                            @class(['bg-amber-100 text-amber-700' => $company->approval_status === 'pending', 'bg-green-100 text-green-700' => $company->approval_status === 'approved', 'bg-red-100 text-red-700' => $company->approval_status === 'rejected'])">
                            {{ ucfirst($company->approval_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $company->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $company->status ? __('Active') : __('Suspended') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.companies.show', $company) }}" class="text-indigo-600 hover:underline">{{ __('Review') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('No companies found.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $companies->links() }}</div>
@endsection
