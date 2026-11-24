<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedJobController extends Controller
{
    public function index(Request $request): View
    {
        $jobs = $request->user()->savedJobs()->with(['company', 'category'])->published()->latest('saved_jobs.created_at')->paginate(10);
        return view('candidate.saved.index', compact('jobs'));
    }

    public function toggle(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);
        $result = $request->user()->savedJobs()->toggle($job->id);
        return back()->with('status', count($result['attached']) ? 'Job saved to your list.' : 'Job removed from your saved list.');
    }
}
