@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Languages') }}</h1>

<div class="bg-white border rounded-xl overflow-hidden mb-6">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Code') }}</th><th class="px-4 py-3">{{ __('RTL') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @foreach ($languages as $language)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        {{ $language->name }}
                        @if ($language->is_default)
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ml-1">{{ __('Default') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $language->code }}</td>
                    <td class="px-4 py-3">{{ $language->is_rtl ? __('Yes') : __('No') }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $language->status ? 'bg-green-100 text-green-700' : 'bg-gray-100' }}">
                            {{ $language->status ? __('Active') : __('Inactive') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-3">
                        @unless ($language->is_default)
                            <form method="POST" action="{{ route('admin.settings.languages.set-default', $language) }}" class="inline">
                                @csrf
                                <button class="text-indigo-600 hover:underline">{{ __('Set Default') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.settings.languages.toggle-status', $language) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-gray-600 hover:underline">{{ $language->status ? __('Disable') : __('Enable') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.settings.languages.destroy', $language) }}" class="inline" onsubmit="return confirm('{{ __('Delete this language?') }}')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-white border rounded-xl p-6 max-w-lg">
    <h2 class="font-medium mb-4">{{ __('Add Language') }}</h2>
    <form method="POST" action="{{ route('admin.settings.languages.store') }}" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-3">
            <input type="text" name="name" placeholder="{{ __('Name (e.g. German)') }}" required class="border rounded-lg px-3 py-2 text-sm">
            <input type="text" name="code" placeholder="{{ __('Code (e.g. de)') }}" required maxlength="10" class="border rounded-lg px-3 py-2 text-sm">
        </div>
        <input type="text" name="locale" placeholder="{{ __('Locale (e.g. de_DE)') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_rtl" value="1"> {{ __('Right-to-left language') }}</label>
        <button class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-indigo-700">{{ __('Add') }}</button>
    </form>
</div>
@endsection
