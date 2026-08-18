<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    public function index(): View
    {
        $savedJobs = Auth::guard('web')->user()
            ->savedJobs()
            ->with(['company', 'type'])
            ->paginate(12);

        return view('seeker.applications.saved', compact('savedJobs'));
    }

    public function toggle(JobListing $jobListing): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user->savedJobs()->where('job_listing_id', $jobListing->id)->exists()) {
            $user->savedJobs()->detach($jobListing->id);
            $message = 'Removed from saved jobs.';
        } else {
            $user->savedJobs()->attach($jobListing->id);
            $message = 'Job saved!';
        }

        return back()->with('status', $message);
    }
}
