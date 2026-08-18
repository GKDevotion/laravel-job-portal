@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $currentLocale = LaravelLocalization::getCurrentLocale();
    $activeCurrency = active_currency();
@endphp

<div class="flex items-center gap-3 text-sm">
    {{-- Language switcher: preserves the current page, just swaps the locale prefix --}}
    <div class="relative group">
        <button type="button" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100">
            {{ strtoupper($currentLocale) }}
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="absolute right-0 mt-1 w-36 bg-white border rounded-lg shadow-lg hidden group-hover:block z-10">
            @foreach (LaravelLocalization::getSupportedLocales() as $code => $properties)
                <a href="{{ LaravelLocalization::getLocalizedURL($code, null, [], true) }}"
                   class="block px-3 py-2 hover:bg-gray-50 {{ $code === $currentLocale ? 'font-semibold text-indigo-600' : '' }}">
                    {{ $properties['native'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Currency switcher: sets ?currency=XXX, SetActiveCurrency middleware persists it to session --}}
    @if ($activeCurrency)
        <form method="GET" class="relative">
            @foreach (request()->except('currency') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="currency" onchange="this.form.submit()" class="appearance-none bg-transparent px-2 py-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                @foreach (\App\Models\Currency::where('status', true)->get() as $currency)
                    <option value="{{ $currency->code }}" @selected($currency->code === $activeCurrency->code)>
                        {{ $currency->code }} ({{ $currency->symbol }})
                    </option>
                @endforeach
            </select>
        </form>
    @endif
</div>
