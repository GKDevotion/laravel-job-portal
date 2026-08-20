<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'companiesCount' => Company::count(),
            'pendingCompaniesCount' => Company::where('approval_status', 'pending')->count(),
            'usersCount' => User::count(),
            'jobsCount' => JobListing::count(),
            'pendingJobsCount' => JobListing::where('status', 'pending_review')->count(),
            'unpaidInvoicesCount' => Invoice::where('status', 'unpaid')->count(),
            'revenueThisMonth' => Invoice::where('status', 'paid')->whereYear('paid_at', now()->year)->whereMonth('paid_at', now()->month)->sum('amount'),
        ]);
    }
}
