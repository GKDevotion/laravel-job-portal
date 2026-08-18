@extends('layouts.app')
@section('title', __('Login'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Job Seeker Login') }}</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember"> {{ __('Remember me') }}
        </label>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Login') }}</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-500">
        {{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-indigo-600">{{ __('Sign up') }}</a>
    </p>
</div>
@endsection
