<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* dompdf renders this in isolation — no external stylesheets, keep it self-contained */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; margin: 0; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company-name { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 24px; margin: 0; color: #111827; }
        .invoice-title p { margin: 4px 0; color: #6b7280; }
        .meta-table { width: 100%; margin-bottom: 30px; }
        .meta-table td { vertical-align: top; padding: 0; }
        .label { color: #6b7280; font-size: 10px; text-transform: uppercase; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { text-align: left; background: #f3f4f6; padding: 8px 12px; font-size: 10px; text-transform: uppercase; color: #6b7280; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        table.items .text-right { text-align: right; }
        .totals { width: 100%; margin-top: 10px; }
        .totals td { padding: 4px 12px; }
        .totals .total-row { font-weight: bold; font-size: 14px; border-top: 2px solid #111827; }
        .status-badge {
            display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: bold;
        }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-unpaid { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company-name">{{ config('app.name') }}</div>
            <p style="color:#6b7280; margin-top:4px;">{{ config('app.url') }}</p>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>{{ $invoice->invoice_number }}</p>
            <span class="status-badge status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                <div class="label">Billed To</div>
                <div><strong>{{ $invoice->billing_name ?? $invoice->company->company_name }}</strong></div>
                @if ($invoice->billing_address)
                    <div>{{ $invoice->billing_address }}</div>
                @endif
                <div>{{ $invoice->company->email }}</div>
            </td>
            <td width="50%">
                <div class="label">Invoice Date</div>
                <div>{{ $invoice->created_at->format('M d, Y') }}</div>
                @if ($invoice->due_date)
                    <div class="label" style="margin-top:8px;">Due Date</div>
                    <div>{{ $invoice->due_date->format('M d, Y') }}</div>
                @endif
                @if ($invoice->isPaid())
                    <div class="label" style="margin-top:8px;">Paid On</div>
                    <div>{{ $invoice->paid_at->format('M d, Y') }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td width="70%"></td>
            <td width="15%">Subtotal</td>
            <td width="15%" class="text-right">{{ $invoice->currency?->symbol }}{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        @if ($invoice->tax_amount > 0)
            <tr>
                <td></td>
                <td>Tax ({{ $invoice->tax_rate }}%)</td>
                <td class="text-right">{{ $invoice->currency?->symbol }}{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td></td>
            <td>Total</td>
            <td class="text-right">{{ $invoice->currency?->symbol }}{{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>

    @if ($invoice->payment_reference)
        <p style="margin-top:20px;"><strong>Payment Reference:</strong> {{ $invoice->payment_reference }}</p>
    @endif

    @if ($invoice->notes)
        <p style="margin-top:20px;">{{ $invoice->notes }}</p>
    @endif

    <div class="footer">
        This invoice was generated by {{ config('app.name') }}. No live payment gateway is
        connected — payment is confirmed manually and reflected here once processed.
    </div>
</body>
</html>
