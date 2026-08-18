@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Currencies') }}</h1>

<div class="bg-white border rounded-xl overflow-hidden mb-6">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Code') }}</th><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Symbol') }}</th><th class="px-4 py-3">{{ __('Exchange Rate') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @foreach ($currencies as $currency)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        {{ $currency->code }}
                        @if ($currency->is_default)
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ml-1">{{ __('Base') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.settings.currencies.update', $currency) }}" class="flex items-center gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $currency->name }}" class="border rounded-lg px-2 py-1 text-sm w-32">
                            <input type="hidden" name="symbol_position" value="{{ $currency->symbol_position }}">
                            <input type="text" name="symbol" value="{{ $currency->symbol }}" class="border rounded-lg px-2 py-1 text-sm w-14">
                            <input type="number" step="0.000001" name="exchange_rate" value="{{ $currency->exchange_rate }}" @disabled($currency->is_default) class="border rounded-lg px-2 py-1 text-sm w-24">
                            <button class="text-indigo-600 hover:underline text-xs">{{ __('Save') }}</button>
                        </form>
                    </td>
                    <td class="px-4 py-3">{{ $currency->symbol }}</td>
                    <td class="px-4 py-3">{{ $currency->exchange_rate }}</td>
                    <td class="px-4 py-3 text-right space-x-3">
                        @unless ($currency->is_default)
                            <form method="POST" action="{{ route('admin.settings.currencies.set-default', $currency) }}" class="inline">
                                @csrf
                                <button class="text-indigo-600 hover:underline">{{ __('Set as Base') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.settings.currencies.toggle-status', $currency) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-gray-600 hover:underline">{{ $currency->status ? __('Disable') : __('Enable') }}</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-white border rounded-xl p-6 max-w-lg">
    <h2 class="font-medium mb-4">{{ __('Add Currency') }}</h2>
    <form method="POST" action="{{ route('admin.settings.currencies.store') }}" class="grid grid-cols-2 gap-3">
        @csrf
        <input type="text" name="name" placeholder="{{ __('Name (e.g. Japanese Yen)') }}" required class="border rounded-lg px-3 py-2 text-sm col-span-2">
        <input type="text" name="code" placeholder="{{ __('Code (e.g. JPY)') }}" required maxlength="3" class="border rounded-lg px-3 py-2 text-sm">
        <input type="text" name="symbol" placeholder="{{ __('Symbol (e.g. ¥)') }}" required class="border rounded-lg px-3 py-2 text-sm">
        <input type="number" step="0.000001" name="exchange_rate" placeholder="{{ __('Rate vs base') }}" required class="border rounded-lg px-3 py-2 text-sm">
        <select name="symbol_position" class="border rounded-lg px-3 py-2 text-sm">
            <option value="before">{{ __('Before amount') }}</option>
            <option value="after">{{ __('After amount') }}</option>
        </select>
        <button class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm col-span-2 hover:bg-indigo-700">{{ __('Add') }}</button>
    </form>
</div>
@endsection
