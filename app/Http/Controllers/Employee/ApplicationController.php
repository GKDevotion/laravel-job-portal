<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    protected const STATUSES = ['submitted', 'viewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected'];

    public function index(Request $request): View
    {
        $employee = Auth::guard('employee')->user();
        abort_unless($employee->can('review applications'), 403);

        $query = JobApplication::whereHas('jobListing', fn ($q) => $q->where('company_id', $employee->company_id))
            ->with(['user', 'jobListing']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return view('employee.applications.index', [
            'applications' => $applications,
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(JobApplication $application): View
    {
        Gate::forUser(Auth::guard('employee')->user())->authorize('view', $application);

        if ($application->status === 'submitted') {
            $application->moveTo('viewed', Auth::guard('employee')->user());
        }

        return view('employee.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $employee = Auth::guard('employee')->user();
        Gate::forUser($employee)->authorize('update', $application);

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $application->moveTo($data['status'], $employee);

        if (isset($data['internal_notes'])) {
            $application->update(['internal_notes' => $data['internal_notes']]);
        }

        return back()->with('status', 'Status updated.');
    }
}
