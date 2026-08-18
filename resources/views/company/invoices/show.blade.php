@extends('layouts.dashboard')
@section('content')
<a href="{{ route('company.invoices.index') }}" class="text-sm text-indigo-600">&larr; {{ __('Back to Invoices') }}</a>

<div class="bg-white border rounded-xl p-6 mt-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold">{{ $invoice->invoice_number }}</h1>
            <p class="text-sm text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full
            @class(['bg-green-100 text-green-700' => $invoice->status === 'paid', 'bg-amber-100 text-amber-700' => $invoice->status === 'unpaid', 'bg-gray-100' => $invoice->status === 'cancelled'])">
            {{ strtoupper($invoice->status) }}
        </span>
    </div>

    <table class="w-full text-sm mb-6">
        <thead class="text-left text-gray-500 border-b">
            <tr><th class="pb-2">{{ __('Description') }}</th><th class="pb-2 text-right">{{ __('Qty') }}</th><th class="pb-2 text-right">{{ __('Unit Price') }}</th><th class="pb-2 text-right">{{ __('Amount') }}</th></tr>
        </thead>
        <tbody class="divide-y">
            @foreach ($invoice->items as $item)
                <tr>
                    <td class="py-2">{{ $item->description }}</td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                    <td class="py-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="py-2 text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end">
        <div class="w-64 space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">{{ __('Subtotal') }}</span><span>{{ number_format($invoice->subtotal, 2) }}</span></div>
            @if ($invoice->tax_amount > 0)
                <div class="flex justify-between"><span class="text-gray-500">{{ __('Tax') }}</span><span>{{ number_format($invoice->tax_amount, 2) }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-base border-t pt-1"><span>{{ __('Total') }}</span><span>{{ $invoice->currency?->symbol }}{{ number_format($invoice->amount, 2) }}</span></div>
        </div>
    </div>

    @if ($invoice->status === 'unpaid')
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
            {{ __('This invoice is awaiting payment confirmation. Complete payment via bank transfer and share your reference with support — an admin will mark this paid once received.') }}
        </div>
    @endif

    <a href="{{ route('company.invoices.download', $invoice) }}" class="inline-block mt-6 bg-gray-800 text-white rounded-lg px-5 py-2 text-sm hover:bg-gray-900">{{ __('Download PDF') }}</a>
</div>
@endsection
