<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LocalizationSettingsService
{
    /**
     * Call this after any create/update/delete on the languages table (Phase 6 admin controllers
     * will call it). AppServiceProvider reads the DB fresh on every request already, so this
     * mainly matters once config caching is introduced — clearing here keeps that safe.
     */
    public function refreshLanguages(): void
    {
        Cache::forget('active_languages');
    }

    public function activeLanguages()
    {
        return Cache::remember('active_languages', now()->addHour(), function () {
            return Language::where('status', true)->orderBy('order')->get();
        });
    }

    public function defaultLanguage(): ?Language
    {
        return Language::where('is_default', true)->first();
    }

    /**
     * Setting a new default currency should be atomic — only one row may have is_default = true.
     */
    public function setDefaultCurrency(Currency $currency): void
    {
        Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
        $currency->update(['is_default' => true, 'exchange_rate' => 1]); // base currency is always rate 1
    }

    public function setDefaultLanguage(Language $language): void
    {
        Language::where('id', '!=', $language->id)->update(['is_default' => false]);
        $language->update(['is_default' => true]);
        $this->refreshLanguages();
    }
}
