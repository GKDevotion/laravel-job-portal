@extends('layouts.app')
@section('title', __('Register Your Company'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Register Your Company') }}</h1>
    <form method="POST" action="{{ route('company.register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Company Name') }}</label>
            <input type="text" name="company_name" value="{{ old('company_name') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('company_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Work Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Industry') }}</label>
            <input type="text" name="industry" value="{{ old('industry') }}" class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Register') }}</button>
        <p class="text-xs text-gray-500 text-center">{{ __('Your account will need admin approval before you can post jobs.') }}</p>
    </form>
</div>
@endsection
