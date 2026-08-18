<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = Auth::guard('company')->user();

        return view('company.dashboard', [
            'company' => $company,
            'activeJobsCount' => $company->jobListings()->where('status', 'published')->count(),
            'totalApplications' => \App\Models\JobApplication::whereHas('jobListing', fn ($q) => $q->where('company_id', $company->id))->count(),
            'recentJobs' => $company->jobListings()->latest()->limit(5)->get(),
            'recentApplications' => \App\Models\JobApplication::whereHas('jobListing', fn ($q) => $q->where('company_id', $company->id))
                ->with(['user', 'jobListing'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
