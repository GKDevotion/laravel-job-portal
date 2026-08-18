<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Auth::guard('company')->user()->invoices()->latest()->paginate(15);

        return view('company.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        abort_unless($invoice->company_id === Auth::guard('company')->id(), 403);

        $invoice->load('items');

        return view('company.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice, InvoiceService $invoiceService): RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless($invoice->company_id === Auth::guard('company')->id(), 403);

        $disk = Storage::disk(config('filesystems.default'));

        // Generate on the fly if it doesn't exist yet (e.g. an older invoice from before
        // PDF generation was wired up, or the file was cleaned up) rather than 404ing.
        if (! $invoice->pdf_path || ! $disk->exists($invoice->pdf_path)) {
            $invoiceService->generatePdf($invoice);
            $invoice->refresh();
        }

        return $disk->download($invoice->pdf_path, "{$invoice->invoice_number}.pdf");
    }
}
