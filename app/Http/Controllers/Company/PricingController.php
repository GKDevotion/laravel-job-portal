<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use App\Models\PricingPlan;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index(): View
    {
        $plans = PricingPlan::where('status', true)->orderBy('order')->get();
        $myJobs = Auth::guard('company')->user()->jobListings()->whereIn('status', ['published', 'pending_review'])->get();

        return view('company.pricing.index', compact('plans', 'myJobs'));
    }

    public function purchase(Request $request, PricingPlan $plan): RedirectResponse
    {
        $company = Auth::guard('company')->user();
        $jobListing = null;

        if ($plan->isFeaturedJobPlan()) {
            $request->validate(['job_listing_id' => ['required', 'exists:job_listings,id']]);

            $jobListing = JobListing::findOrFail($request->integer('job_listing_id'));
            Gate::forUser($company)->authorize('update', $jobListing);
        }

        $invoice = $this->invoiceService->createForPlanPurchase($company, $plan, $jobListing);

        return redirect()->route('company.invoices.show', $invoice)
            ->with('status', 'Invoice created. Complete payment using the instructions on the invoice, then contact support with your payment reference.');
    }
}
