<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobListing\StoreJobListingRequest;
use App\Http\Requests\JobListing\UpdateJobListingRequest;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\JobType;
use App\Models\Skill;
use App\Services\JobListingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function __construct(protected JobListingService $jobListingService) {}

    public function index(): View
    {
        $jobs = Auth::guard('company')->user()
            ->jobListings()
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return view('company.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('company.jobs.create', $this->formData());
    }

    public function store(StoreJobListingRequest $request): RedirectResponse
    {
        $company = Auth::guard('company')->user();

        abort_unless($company->isApproved(), 403, 'Your company must be approved by an admin before posting jobs.');

        $job = $this->jobListingService->create($request->validated(), $company);

        if ($request->filled('skills')) {
            $job->skills()->sync($request->input('skills'));
        }

        return redirect()->route('company.jobs.edit', $job)
            ->with('status', 'Job saved as draft. Submit it for review when ready to publish.');
    }

    public function edit(JobListing $job): View
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('update', $job);

        return view('company.jobs.edit', array_merge(['job' => $job], $this->formData()));
    }

    public function update(UpdateJobListingRequest $request, JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('update', $job);

        $this->jobListingService->update($job, $request->validated());

        return back()->with('status', 'Job updated.');
    }

    public function submitForReview(JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('update', $job);

        try {
            $this->jobListingService->submitForReview($job);
        } catch (\App\Exceptions\InsufficientCreditsException $e) {
            return redirect()->route('company.pricing.index')->with('error', $e->getMessage());
        }

        return back()->with('status', 'Submitted for admin review — it will go live once approved.');
    }

    public function close(JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('update', $job);

        $this->jobListingService->close($job);

        return back()->with('status', 'Job closed — no longer accepting applications.');
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('company')->user())->authorize('delete', $job);

        $job->delete();

        return redirect()->route('company.jobs.index')->with('status', 'Job deleted.');
    }

    protected function formData(): array
    {
        return [
            'categories' => JobCategory::where('status', true)->orderBy('order')->get(),
            'types' => JobType::where('status', true)->get(),
            'skills' => Skill::orderBy('name')->get(),
        ];
    }
}
