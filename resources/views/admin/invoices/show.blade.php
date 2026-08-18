@extends('layouts.admin')
@section('content')
<a href="{{ route('admin.invoices.index') }}" class="text-sm text-indigo-600">&larr; {{ __('Back to Invoices') }}</a>

<div class="bg-white border rounded-xl p-6 mt-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold">{{ $invoice->invoice_number }}</h1>
            <p class="text-sm text-gray-500">{{ $invoice->company->company_name }} &middot; {{ $invoice->created_at->format('M d, Y') }}</p>
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

    <div class="flex justify-end mb-6">
        <div class="w-64 space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">{{ __('Subtotal') }}</span><span>{{ number_format($invoice->subtotal, 2) }}</span></div>
            @if ($invoice->tax_amount > 0)
                <div class="flex justify-between"><span class="text-gray-500">{{ __('Tax') }}</span><span>{{ number_format($invoice->tax_amount, 2) }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-base border-t pt-1"><span>{{ __('Total') }}</span><span>{{ $invoice->currency?->symbol }}{{ number_format($invoice->amount, 2) }}</span></div>
        </div>
    </div>

    @if ($invoice->status === 'unpaid')
        <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="flex gap-3 items-end border-t pt-6">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">{{ __('Payment Reference (optional)') }}</label>
                <input type="text" name="payment_reference" placeholder="{{ __('Bank transfer ref, transaction ID, etc.') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-green-600 text-white rounded-lg px-5 py-2 text-sm hover:bg-green-700">{{ __('Mark as Paid') }}</button>
        </form>
        <form method="POST" action="{{ route('admin.invoices.cancel', $invoice) }}" class="mt-3" onsubmit="return confirm('{{ __('Cancel this invoice?') }}')">
            @csrf
            <button class="text-red-600 text-sm hover:underline">{{ __('Cancel Invoice') }}</button>
        </form>
    @elseif ($invoice->status === 'paid')
        <p class="text-sm text-gray-500 border-t pt-6">
            {{ __('Paid on :date by :admin', ['date' => $invoice->paid_at->format('M d, Y'), 'admin' => $invoice->markedPaidBy?->name ?? '—']) }}
            @if ($invoice->payment_reference) &middot; {{ __('Ref') }}: {{ $invoice->payment_reference }} @endif
        </p>
    @endif
</div>
@endsection
