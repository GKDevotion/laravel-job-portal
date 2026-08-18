<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seeker\ApplyJobRequest;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $applications = Auth::guard('web')->user()
            ->applications()
            ->with('jobListing.company')
            ->latest()
            ->paginate(10);

        return view('seeker.applications.index', compact('applications'));
    }

    public function store(ApplyJobRequest $request, JobListing $jobListing): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        abort_unless($jobListing->isOpen(), 404, 'This job is no longer accepting applications.');

        if ($jobListing->applications()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied to this job.');
        }

        // Resume: either the freshly uploaded file, or the seeker's saved profile resume
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes/'.$user->id, 'public');
        } elseif ($request->boolean('use_profile_resume') && $user->resume) {
            $resumePath = $user->resume;
        } else {
            return back()->withErrors(['resume' => 'A resume is required to apply.'])->withInput();
        }

        JobApplication::create([
            'job_listing_id' => $jobListing->id,
            'user_id' => $user->id,
            'resume_path' => $resumePath,
            'cover_letter' => $request->input('cover_letter'),
            'status' => 'submitted',
            'status_updated_at' => now(),
        ]);

        $jobListing->increment('applications_count');

        return redirect()->route('jobs.show', $jobListing)
            ->with('status', 'Application submitted! The employer will review it soon.');
    }

    public function withdraw(JobApplication $application): RedirectResponse
    {
        abort_unless($application->user_id === Auth::guard('web')->id(), 403);
        abort_if(in_array($application->status, ['hired', 'rejected']), 422, 'This application can no longer be withdrawn.');

        $application->moveTo('withdrawn');

        return back()->with('status', 'Application withdrawn.');
    }
}
