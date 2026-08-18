<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::withCount('jobListings');

        if ($request->filled('status')) {
            $query->where('approval_status', $request->string('status'));
        }

        if ($keyword = $request->string('q')->trim()->toString()) {
            $query->where('company_name', 'like', "%{$keyword}%");
        }

        $companies = $query->latest()->paginate(20)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function show(Company $company): View
    {
        $company->load(['employees', 'jobListings' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.companies.show', compact('company'));
    }

    public function approve(Company $company): RedirectResponse
    {
        $company->update(['approval_status' => 'approved', 'is_verified' => true]);

        return back()->with('status', "{$company->company_name} approved — they can now publish jobs.");
    }

    public function reject(Request $request, Company $company): RedirectResponse
    {
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:1000']]);

        $company->update(['approval_status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);

        return back()->with('status', "{$company->company_name} rejected.");
    }

    public function toggleStatus(Company $company): RedirectResponse
    {
        $company->update(['status' => ! $company->status]);

        return back()->with('status', $company->status ? 'Company reactivated.' : 'Company suspended.');
    }
}
