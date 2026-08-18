@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Invoices') }}</h1>
<form method="GET" class="mb-6 text-sm">
    <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
        <option value="">{{ __('All Statuses') }}</option>
        @foreach (['unpaid', 'paid', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</form>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Invoice #') }}</th><th class="px-4 py-3">{{ __('Company') }}</th><th class="px-4 py-3">{{ __('Amount') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($invoices as $invoice)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $invoice->company->company_name }}</td>
                    <td class="px-4 py-3">{{ $invoice->currency?->symbol }}{{ number_format($invoice->amount, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full
                            @class(['bg-green-100 text-green-700' => $invoice->status === 'paid', 'bg-amber-100 text-amber-700' => $invoice->status === 'unpaid', 'bg-gray-100' => $invoice->status === 'cancelled'])">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.invoices.show', $invoice) }}" class="text-indigo-600 hover:underline">{{ __('Review') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('No invoices yet.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
