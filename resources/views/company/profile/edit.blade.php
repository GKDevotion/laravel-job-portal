@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Company Profile') }}</h1>

<div class="grid grid-cols-3 gap-6">
    <div class="bg-white border rounded-xl p-6 text-center">
        @if ($url = $company->logoUrl())
            <img src="{{ $url }}" class="w-24 h-24 rounded-lg mx-auto object-cover mb-4">
        @else
            <div class="w-24 h-24 rounded-lg mx-auto bg-gray-100 flex items-center justify-center mb-4 text-gray-400 text-xs">{{ __('No logo') }}</div>
        @endif
        <form method="POST" action="{{ route('company.profile.logo') }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <input type="file" name="logo" accept="image/*" class="text-xs w-full">
            <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 text-sm hover:bg-indigo-700">{{ __('Update Logo') }}</button>
        </form>

        <div class="mt-6 pt-6 border-t">
            @if ($url = $company->coverImageUrl())
                <img src="{{ $url }}" class="w-full h-20 rounded-lg object-cover mb-2">
            @endif
            <form method="POST" action="{{ route('company.profile.cover') }}" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="file" name="cover_image" accept="image/*" class="text-xs w-full">
                <button type="submit" class="w-full border rounded-lg py-2 text-sm hover:bg-gray-50">{{ __('Update Cover Image') }}</button>
            </form>
        </div>
    </div>

    <div class="col-span-2 bg-white border rounded-xl p-6">
        <form method="POST" action="{{ route('company.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Company Name') }}</label>
                <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('About') }}</label>
                <textarea name="about" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('about', $company->about) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Industry') }}</label>
                    <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Website') }}</label>
                    <input type="url" name="website" value="{{ old('website', $company->website) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Country') }}</label>
                    <input type="text" name="country" value="{{ old('country', $company->country) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('City') }}</label>
                    <input type="text" name="city" value="{{ old('city', $company->city) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Save Changes') }}</button>
        </form>
    </div>
</div>
@endsection
