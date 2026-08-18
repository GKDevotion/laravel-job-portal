@extends('layouts.app')
@section('title', __('Team Login'))
@section('content')
<div class="max-w-md mx-auto bg-white border rounded-xl p-8">
    <h1 class="text-xl font-bold mb-6">{{ __('Team Member Login') }}</h1>
    <form method="POST" action="{{ route('employee.login') }}" class="space-y-4">
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
        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">{{ __('Login') }}</button>
    </form>
</div>
@endsection
