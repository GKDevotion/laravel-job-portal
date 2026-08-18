<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::where('approval_status', 'approved')->where('status', true)
            ->withCount(['jobListings' => fn ($q) => $q->published()]);

        if ($keyword = $request->string('q')->trim()->toString()) {
            $query->where('company_name', 'like', "%{$keyword}%");
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->string('industry'));
        }

        $companies = $query->orderByDesc('job_listings_count')->paginate(15)->withQueryString();

        return view('front.companies.index', compact('companies'));
    }

    public function show(Company $company): View
    {
        abort_unless($company->approval_status === 'approved' && $company->status, 404);

        $jobs = $company->jobListings()->published()->latest('published_at')->paginate(10);

        return view('front.companies.show', compact('company', 'jobs'));
    }
}
