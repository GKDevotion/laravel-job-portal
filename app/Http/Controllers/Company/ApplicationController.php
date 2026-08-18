<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    // Valid forward/backward moves in the hiring pipeline
    protected const STATUSES = ['submitted', 'viewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected'];

    public function index(Request $request): View
    {
        $company = Auth::guard('company')->user();

        $query = JobApplication::whereHas('jobListing', fn ($q) => $q->where('company_id', $company->id))
            ->with(['user', 'jobListing']);

        if ($request->filled('job')) {
            $query->where('job_listing_id', $request->integer('job'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return view('company.applications.index', [
            'applications' => $applications,
            'jobs' => $company->jobListings()->select('id', 'title')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(JobApplication $application): View
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('view', $application);

        // Mark as viewed the first time an employer opens it
        if ($application->status === 'submitted') {
            $application->moveTo('viewed');
        }

        $application->load(['user', 'jobListing']);

        return view('company.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('update', $application);

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $application->moveTo($data['status']);

        if (isset($data['internal_notes'])) {
            $application->update(['internal_notes' => $data['internal_notes']]);
        }

        return back()->with('status', 'Application status updated to "'.$data['status'].'".');
    }
}
