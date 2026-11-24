<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = JobApplication::with(['job.company', 'job.category'])
            ->where('candidate_id', $request->user()->id)->latest()->paginate(10);
        return view('candidate.applications.index', compact('applications'));
    }

    public function store(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published' && (!$job->expires_at || $job->expires_at->toDateString() >= now()->toDateString()), 404);
        $candidate = $request->user();
        $profile = $candidate->candidateProfile;
        abort_unless($profile, 403, 'Please complete your candidate profile before applying.');
        $data = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:10000'],
            'resume' => [$profile->resume_path ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);
        if (JobApplication::where('job_id', $job->id)->where('candidate_id', $candidate->id)->exists()) {
            return back()->with('status', 'You have already applied for this job.');
        }
        $resumePath = $profile->resume_path;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('jobstock/applications', 'public');
        }
        JobApplication::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'submitted',
        ]);
        return redirect()->route('candidate.applications.index')->with('status', 'Your application has been submitted successfully.');
    }

    public function withdraw(Request $request, JobApplication $application): RedirectResponse
    {
        abort_unless($application->candidate_id === $request->user()->id, 403);
        abort_unless($application->status === 'submitted', 403, 'Only newly submitted applications can be withdrawn.');
        $application->update(['status' => 'withdrawn']);
        return back()->with('status', 'Application withdrawn.');
    }
}
