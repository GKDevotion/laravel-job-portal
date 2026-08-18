@extends('layouts.app')
@section('title', __('Set Your Password'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Set Your Password') }}</h1>
    <form method="POST" action="{{ route('employee.set-password') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('New Password') }}</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Set Password') }}</button>
    </form>
</div>
@endsection
