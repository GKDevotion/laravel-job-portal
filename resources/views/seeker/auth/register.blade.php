@extends('layouts.app')
@section('title', __('Sign Up'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Create Your Job Seeker Account') }}</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Full Name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Create Account') }}</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-500">
        {{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-indigo-600">{{ __('Login') }}</a>
    </p>
</div>
@endsection
