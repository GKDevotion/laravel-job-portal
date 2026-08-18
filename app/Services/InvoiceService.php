<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\JobListing;
use App\Models\PricingPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Create an unpaid invoice for a company purchasing a pricing plan. No money moves here —
     * this just records what's owed. Payment confirmation is a separate manual step (markPaid)
     * since there's no live gateway yet; a company could pay by bank transfer, and an admin
     * confirms it happened.
     */
    public function createForPlanPurchase(Company $company, PricingPlan $plan, ?JobListing $jobListing = null): Invoice
    {
        $taxRate = 0; // no tax engine yet — plug a real one in here if/when needed (e.g. VAT by country)
        $subtotal = (float) $plan->price;
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = $subtotal + $taxAmount;

        $invoice = Invoice::create([
            'invoice_number' => $this->nextInvoiceNumber(),
            'company_id' => $company->id,
            'pricing_plan_id' => $plan->id,
            'job_listing_id' => $jobListing?->id,
            'description' => $plan->name,
            'amount' => $total,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'currency_id' => $plan->currency_id,
            'billing_name' => $company->company_name,
            'billing_address' => implode(', ', array_filter([$company->address, $company->city, $company->country])),
            'due_date' => now()->addDays(7),
            'status' => 'unpaid',
        ]);

        $invoice->items()->create([
            'description' => $plan->name.($jobListing ? ' — '.$jobListing->title : ''),
            'quantity' => 1,
            'unit_price' => $subtotal,
            'amount' => $subtotal,
        ]);

        return $invoice;
    }

    /**
     * Admin confirms payment was received (bank transfer, manual reconciliation, etc).
     * Applies whatever the underlying plan grants, then generates the PDF receipt.
     */
    public function markPaid(Invoice $invoice, Admin $admin, ?string $paymentReference = null): Invoice
    {
        if ($invoice->isPaid()) {
            return $invoice; // idempotent — don't double-grant credits if clicked twice
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'marked_paid_by' => $admin->id,
            'payment_reference' => $paymentReference,
        ]);

        $this->applyPlanEffect($invoice);
        $this->generatePdf($invoice);

        return $invoice->fresh();
    }

    public function cancel(Invoice $invoice): Invoice
    {
        $invoice->update(['status' => 'cancelled']);

        return $invoice;
    }

    /**
     * Grant whatever the purchased plan promised: job posting credits added to the company's
     * balance, or a job listing flagged as featured for N days.
     */
    protected function applyPlanEffect(Invoice $invoice): void
    {
        $plan = $invoice->pricingPlan;

        if (! $plan) {
            return; // manually-created invoice with no linked plan — nothing to auto-grant
        }

        if ($plan->isCreditsPlan() && $plan->credits) {
            $invoice->company->addCredits($plan->credits);
        }

        if ($plan->isFeaturedJobPlan() && $invoice->jobListing) {
            $invoice->jobListing->update(['is_featured' => true]);
            // Note: featured_days is tracked on the plan but expiring the boost automatically
            // needs a scheduled job — add a `featured_until` timestamp + a scheduled command
            // here if/when automatic expiry matters; documented as a known gap for now.
        }
    }

    public function generatePdf(Invoice $invoice): string
    {
        $invoice->load(['items', 'company', 'currency']);

        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);

        $path = "invoices/{$invoice->invoice_number}.pdf";
        Storage::disk(config('filesystems.default'))->put($path, $pdf->output());

        $invoice->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * Sequential, human-readable invoice numbers: INV-2026-000001. Scoped per calendar year
     * so numbering resets cleanly each January without ever colliding.
     */
    protected function nextInvoiceNumber(): string
    {
        $year = now()->year;
        $count = Invoice::whereYear('created_at', $year)->count() + 1;

        return sprintf('INV-%d-%06d', $year, $count);
    }
}
