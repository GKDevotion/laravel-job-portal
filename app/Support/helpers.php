<?php

use App\Models\Currency;
use App\Models\Language;

if (! function_exists('active_currency')) {
    /**
     * The Currency model the current visitor is browsing in (session override, else site default).
     * Bound once per request in AppServiceProvider so this never re-queries.
     */
    function active_currency(): ?Currency
    {
        return app()->bound('currency.active') ? app('currency.active') : null;
    }
}

if (! function_exists('money')) {
    /**
     * Format an amount that is stored in the site's BASE currency into the visitor's active
     * currency. All monetary values in the DB (job salaries, invoice amounts) are stored in
     * the base currency (exchange_rate = 1 row) — this is the one place conversion happens,
     * so every screen in the app stays consistent even if the active currency changes mid-session.
     */
    function money(?float $amountInBaseCurrency): string
    {
        if ($amountInBaseCurrency === null) {
            return '—';
        }

        $currency = active_currency();

        if (! $currency) {
            return number_format($amountInBaseCurrency, 2);
        }

        return $currency->format($amountInBaseCurrency);
    }
}

if (! function_exists('current_language')) {
    /**
     * The Language model matching the active app locale — gives Blade access to is_rtl,
     * native name, flag, etc. without a fresh query on every call site.
     */
    function current_language(): ?Language
    {
        static $cached = null;

        if ($cached === null) {
            $cached = Language::where('code', app()->getLocale())->first() ?? false;
        }

        return $cached ?: null;
    }
}
