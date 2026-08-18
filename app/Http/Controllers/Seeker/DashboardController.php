<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('web')->user();

        return view('seeker.dashboard', [
            'user' => $user,
            'recentApplications' => $user->applications()->with('jobListing.company')->latest()->limit(5)->get(),
            'savedJobsCount' => $user->savedJobs()->count(),
            'applicationsCount' => $user->applications()->count(),
        ]);
    }
}
