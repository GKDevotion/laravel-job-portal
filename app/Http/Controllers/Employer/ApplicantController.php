<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicantController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->user()->employerProfile?->company;
        abort_unless($company, 403, 'Complete your company profile first.');
        $applications = JobApplication::with(['job', 'candidate.candidateProfile'])
            ->whereHas('job', fn ($query) => $query->where('company_id', $company->id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()->paginate(15)->withQueryString();
        return view('employer.applications.index', compact('applications'));
    }

    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $company = $request->user()->employerProfile?->company;
        abort_unless($company && $application->job()->where('company_id', $company->id)->exists(), 403);
        $data = $request->validate([
            'status' => ['required', Rule::in(['reviewing', 'shortlisted', 'interview', 'rejected', 'hired'])],
            'employer_notes' => ['nullable', 'string', 'max:5000'],
        ]);
        $data['reviewed_at'] = now();
        $application->update($data);
        return back()->with('status', 'Applicant status updated.');
    }
}
