@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Invite Employee') }}</h1>
<form method="POST" action="{{ route('company.employees.store') }}" class="space-y-4 bg-white border rounded-xl p-6 max-w-lg">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Job Title') }}</label>
        <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Department') }}</label>
        <select name="department" class="w-full border rounded-lg px-3 py-2">
            @foreach (['hr' => 'HR', 'recruitment' => 'Recruitment', 'management' => 'Management', 'other' => 'Other'] as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Role / Permissions') }}</label>
        <select name="role" class="w-full border rounded-lg px-3 py-2">
            <option value="HR Manager">{{ __('HR Manager — full access') }}</option>
            <option value="Recruiter">{{ __('Recruiter — post jobs & review applicants') }}</option>
            <option value="Viewer">{{ __('Viewer — review applicants only') }}</option>
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Send Invitation') }}</button>
</form>
@endsection
