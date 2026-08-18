<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\InviteEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $company = Auth::guard('company')->user();

        $employees = $company->employees()->with('invitedBy')->latest()->paginate(15);

        return view('company.employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('company.employees.create');
    }

    public function store(InviteEmployeeRequest $request): RedirectResponse
    {
        $company = Auth::guard('company')->user();
        $data = $request->validated();

        // Temporary random password — the invited employee sets their own via the
        // standard password-reset flow scoped to the "employees" broker.
        $employee = Employee::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Str::random(24),
            'job_title' => $data['job_title'] ?? null,
            'department' => $data['department'],
            'status' => true,
        ]);

        $employee->assignRole($data['role']);

        // Send them a password-set link on the "employees" broker so they can log in
        Password::broker('employees')->sendResetLink(['email' => $employee->email]);

        return redirect()->route('company.employees.index')
            ->with('status', "Invitation sent to {$employee->email}.");
    }

    public function toggleStatus(Employee $employee): RedirectResponse
    {
        abort_unless($employee->company_id === Auth::guard('company')->id(), 403);

        $employee->update(['status' => ! $employee->status]);

        return back()->with('status', $employee->status ? 'Employee reactivated.' : 'Employee suspended.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        abort_unless($employee->company_id === Auth::guard('company')->id(), 403);

        $employee->delete();

        return back()->with('status', 'Employee removed from the team.');
    }
}
