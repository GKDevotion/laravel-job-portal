<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index(Request $request): View
    {
        $query = Invoice::with('company')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['items', 'company', 'pricingPlan', 'jobListing']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function markPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $this->invoiceService->markPaid($invoice, Auth::guard('admin')->user(), $data['payment_reference'] ?? null);

        return back()->with('status', "Invoice {$invoice->invoice_number} marked as paid. Any credits/features it grants have been applied.");
    }

    public function cancel(Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->cancel($invoice);

        return back()->with('status', "Invoice {$invoice->invoice_number} cancelled.");
    }
}
