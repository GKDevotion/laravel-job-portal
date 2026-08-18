<?php

namespace App\Http\Requests\Seeker;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        return [
            // Optional: seeker may reuse their profile resume instead of uploading a new one
            'resume' => ['required_without:use_profile_resume', 'nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'use_profile_resume' => ['nullable', 'boolean'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'resume.required_without' => 'Upload a resume, or add one to your profile first to reuse it.',
        ];
    }
}
