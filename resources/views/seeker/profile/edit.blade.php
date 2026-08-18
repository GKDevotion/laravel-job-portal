@extends('layouts.app')
@section('title', __('My Profile'))
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('My Profile') }}</h1>

<div class="grid grid-cols-3 gap-6">
    <div class="bg-white border rounded-xl p-6 text-center">
        <img src="{{ $user->avatarUrl() }}" class="w-24 h-24 rounded-full mx-auto object-cover mb-4" alt="{{ $user->name }}">
        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <input type="file" name="avatar" accept="image/*" class="text-xs w-full">
            <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 text-sm hover:bg-indigo-700">{{ __('Update Photo') }}</button>
        </form>

        <div class="mt-6 pt-6 border-t text-left">
            <p class="text-sm font-medium mb-2">{{ __('Resume') }}</p>
            @if ($media = $user->getFirstMedia('resume'))
                <a href="{{ $media->getUrl() }}" target="_blank" class="text-indigo-600 text-sm hover:underline">{{ __('View current resume') }}</a>
            @else
                <p class="text-xs text-gray-500">{{ __('No resume uploaded yet.') }}</p>
            @endif
            <form method="POST" action="{{ route('profile.resume') }}" enctype="multipart/form-data" class="space-y-2 mt-2">
                @csrf
                <input type="file" name="resume" accept=".pdf,.doc,.docx" class="text-xs w-full">
                <button type="submit" class="w-full border rounded-lg py-2 text-sm hover:bg-gray-50">{{ __('Upload New Resume') }}</button>
            </form>
        </div>
    </div>

    <div class="col-span-2 bg-white border rounded-xl p-6">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Full Name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Current Designation') }}</label>
                <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Bio') }}</label>
                <textarea name="bio" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Country') }}</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('City') }}</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_open_to_work" value="1" @checked($user->is_open_to_work)>
                {{ __('Open to work') }}
            </label>
            <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Save Changes') }}</button>
        </form>
    </div>
</div>
@endsection
