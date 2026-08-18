<?php

namespace App\Http\Controllers\Employee;

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
        $employee = Auth::guard('employee')->user();

        // Employees only ever see jobs belonging to THEIR company — never a cross-company query
        $jobs = JobListing::where('company_id', $employee->company_id)
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return view('employee.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        abort_unless(Auth::guard('employee')->user()->can('post jobs'), 403);

        return view('employee.jobs.create', $this->formData());
    }

    public function store(StoreJobListingRequest $request): RedirectResponse
    {
        $employee = Auth::guard('employee')->user();
        abort_unless($employee->can('post jobs'), 403);
        abort_unless($employee->company->isApproved(), 403, 'Your company must be approved before posting jobs.');

        $job = $this->jobListingService->create($request->validated(), $employee->company, $employee);

        if ($request->filled('skills')) {
            $job->skills()->sync($request->input('skills'));
        }

        return redirect()->route('employee.jobs.edit', $job)->with('status', 'Job saved as draft.');
    }

    public function edit(JobListing $job): View
    {
        Gate::forUser(Auth::guard('employee')->user())->authorize('update', $job);

        return view('employee.jobs.edit', array_merge(['job' => $job], $this->formData()));
    }

    public function update(UpdateJobListingRequest $request, JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('employee')->user())->authorize('update', $job);

        $this->jobListingService->update($job, $request->validated());

        return back()->with('status', 'Job updated.');
    }

    public function submitForReview(JobListing $job): RedirectResponse
    {
        Gate::forUser(Auth::guard('employee')->user())->authorize('update', $job);

        try {
            $this->jobListingService->submitForReview($job);
        } catch (\App\Exceptions\InsufficientCreditsException $e) {
            return redirect()->route('employee.jobs.index')->with('error', $e->getMessage().' Ask your company owner to purchase more credits.');
        }

        return back()->with('status', 'Submitted for admin review.');
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
