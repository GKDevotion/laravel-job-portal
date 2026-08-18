<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $employee = Auth::guard('employee')->user();

        return view('employee.dashboard', [
            'employee' => $employee,
            'myPostedJobsCount' => $employee->company->jobListings()->where('posted_by', $employee->id)->count(),
            'pendingReviewCount' => JobApplication::whereHas(
                'jobListing', fn ($q) => $q->where('company_id', $employee->company_id)
            )->where('status', 'submitted')->count(),
        ]);
    }
}
