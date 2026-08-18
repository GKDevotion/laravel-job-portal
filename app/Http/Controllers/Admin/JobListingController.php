<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobListing::with(['company', 'category']);

        $query->where('status', $request->string('status')->toString() ?: 'pending_review');

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function show(JobListing $job): View
    {
        $job->load(['company', 'category', 'type', 'skills']);

        return view('admin.jobs.show', compact('job'));
    }

    public function approve(JobListing $job): RedirectResponse
    {
        $job->update([
            'status' => 'published',
            'published_at' => $job->published_at ?? now(), // keep original publish date if this is a re-approval after an edit
            'rejection_reason' => null,
        ]);

        return back()->with('status', "\"{$job->title}\" is now live.");
    }

    public function reject(Request $request, JobListing $job): RedirectResponse
    {
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:1000']]);

        $job->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);

        return back()->with('status', "\"{$job->title}\" rejected — the employer can revise and resubmit.");
    }
}
