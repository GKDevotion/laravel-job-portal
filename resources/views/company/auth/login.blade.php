@extends('layouts.app')
@section('title', __('Employer Login'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Employer Login') }}</h1>
    <form method="POST" action="{{ route('company.login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Company Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Login') }}</button>
    </form>
    <p class="text-sm text-center mt-4 text-gray-500">
        {{ __('New employer?') }} <a href="{{ route('company.register') }}" class="text-indigo-600">{{ __('Register your company') }}</a>
    </p>
</div>
@endsection
