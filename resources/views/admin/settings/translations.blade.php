@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-2">{{ __('Translation Tool') }}</h1>
<p class="text-sm text-gray-500 mb-6">{{ __('Edit front theme and admin panel strings per language. Leave a field blank to fall back to the original English text.') }}</p>

<form method="GET" class="mb-6">
    <select name="locale" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
        @foreach ($languages as $language)
            <option value="{{ $language->code }}" @selected($currentLocale === $language->code)>{{ $language->name }} ({{ $language->code }})</option>
        @endforeach
    </select>
</form>

<form method="POST" action="{{ route('admin.settings.translations.update') }}">
    @csrf
    <input type="hidden" name="locale" value="{{ $currentLocale }}">

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr><th class="px-4 py-3 w-1/2">{{ __('Original (English)') }}</th><th class="px-4 py-3">{{ __('Translation') }}</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($strings as $key => $value)
                    <tr>
                        <td class="px-4 py-3 text-gray-500">{{ $key }}</td>
                        <td class="px-4 py-3">
                            <input type="text" name="translations[{{ $key }}]" value="{{ $value }}" class="w-full border rounded-lg px-3 py-1.5 text-sm">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-8 text-center text-gray-500">{{ __('No strings found yet — they appear here once used with __() somewhere in the app.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (count($strings))
        <button type="submit" class="mt-4 bg-indigo-600 text-white rounded-lg px-5 py-2 text-sm hover:bg-indigo-700">{{ __('Save Translations') }}</button>
    @endif
</form>
@endsection
