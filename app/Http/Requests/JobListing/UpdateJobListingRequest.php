<?php

namespace App\Http\Requests\JobListing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Actual "can this actor post jobs" check happens via policy/permission in the controller;
        // this just confirms *someone* is authenticated on a company-side guard.
        return auth('company')->check() || auth('employee')->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:150'],
            'description' => ['required', 'array'],
            'description.en' => ['required', 'string'],
            'responsibilities' => ['nullable', 'array'],
            'requirements' => ['nullable', 'array'],
            'benefits' => ['nullable', 'array'],

            'job_category_id' => ['required', 'exists:job_categories,id'],
            'job_type_id' => ['required', 'exists:job_types,id'],
            'currency_id' => ['nullable', 'exists:currencies,id'],

            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'gte:salary_min'],
            'salary_period' => ['required', 'in:hourly,daily,weekly,monthly,yearly'],
            'is_salary_negotiable' => ['boolean'],

            'work_mode' => ['required', 'in:onsite,remote,hybrid'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],

            'experience_level' => ['nullable', 'string', 'max:50'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'application_deadline' => ['nullable', 'date', 'after:today'],

            'skills' => ['nullable', 'array'],
            'skills.*' => ['exists:skills,id'],

            'meta_title' => ['nullable', 'array'],
            'meta_description' => ['nullable', 'array'],
        ];
    }
}
