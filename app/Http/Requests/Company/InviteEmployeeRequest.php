<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class InviteEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Full permission check (scoped to company) happens in the controller/policy;
        // this just gates on "is a company or an employee with manage-team rights" being logged in.
        return auth('company')->check()
            || (auth('employee')->check() && auth('employee')->user()->can('manage team'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:employees,email'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'department' => ['required', 'in:hr,recruitment,management,other'],
            'role' => ['required', 'in:HR Manager,Recruiter,Viewer'],
        ];
    }
}
