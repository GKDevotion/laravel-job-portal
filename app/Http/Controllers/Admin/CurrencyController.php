<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\LocalizationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function __construct(protected LocalizationSettingsService $localizationService) {}

    public function index(): View
    {
        return view('admin.settings.currencies', ['currencies' => Currency::orderBy('code')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'size:3', 'unique:currencies,code'],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'symbol_position' => ['required', 'in:before,after'],
        ]);

        $data['code'] = strtoupper($data['code']);
        Currency::create($data + ['status' => true]);

        return back()->with('status', 'Currency added.');
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'symbol_position' => ['required', 'in:before,after'],
        ]);

        // The default/base currency's rate is always exactly 1 by definition — everything
        // else is relative to it. Silently ignore attempts to change it from the edit form
        // rather than letting an admin accidentally break every price on the site.
        if ($currency->is_default) {
            unset($data['exchange_rate']);
        }

        $currency->update($data);

        return back()->with('status', 'Currency updated.');
    }

    public function setDefault(Currency $currency): RedirectResponse
    {
        $this->localizationService->setDefaultCurrency($currency);

        return back()->with('status', "{$currency->code} is now the base currency. All other rates are relative to it.");
    }

    public function toggleStatus(Currency $currency): RedirectResponse
    {
        if ($currency->is_default) {
            return back()->with('error', 'Cannot disable the base currency.');
        }

        $currency->update(['status' => ! $currency->status]);

        return back()->with('status', 'Currency updated.');
    }
}
