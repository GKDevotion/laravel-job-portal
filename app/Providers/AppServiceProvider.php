<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadLocalesFromDatabase();
        $this->bindActiveCurrency();
        $this->configurePasswordResetUrls();
    }

    /**
     * Laravel's default ResetPassword notification builds its link via
     * route('password.reset', [...]), which doesn't exist in this app — every guard here
     * (admin/company/web/employee) uses its own custom-named routes instead. Without this,
     * calling Password::broker('employees')->sendResetLink() (used by the "invite employee"
     * flow) throws a RouteNotFoundException the moment the notification tries to render.
     */
    protected function configurePasswordResetUrls(): void
    {
        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $email = urlencode($notifiable->getEmailForPasswordReset());

            return match (get_class($notifiable)) {
                \App\Models\Employee::class => route('employee.set-password.show', ['token' => $token, 'email' => $email]),
                // Company and job-seeker "forgot password" UI isn't built yet (see README) —
                // this still prevents a hard crash if sendResetLink() is ever called for them,
                // pointing at their login page with the token/email as a starting point.
                \App\Models\Company::class => route('company.login', ['token' => $token, 'email' => $email]),
                \App\Models\User::class => route('login', ['token' => $token, 'email' => $email]),
                default => route('admin.login', ['token' => $token, 'email' => $email]),
            };
        });
    }

    /**
     * The `languages` table (managed in the admin panel) is the real source of truth for
     * which locales are enabled — not the static config file. We rewrite the package config
     * at boot so admins can add/remove/reorder languages with zero code changes or deploys.
     */
    protected function loadLocalesFromDatabase(): void
    {
        // Guard against running before migrations exist (fresh installs, `artisan config:cache` in CI, etc.)
        if (! Schema::hasTable('languages')) {
            return;
        }

        $languages = Language::where('status', true)->orderBy('order')->get();

        if ($languages->isEmpty()) {
            return;
        }

        $supported = [];
        foreach ($languages as $lang) {
            $supported[$lang->code] = [
                'name' => $lang->name,
                'script' => $lang->is_rtl ? 'Arab' : 'Latn',
                'native' => $lang->name,
                'regional' => $lang->locale,
            ];
        }

        config([
            'laravellocalization.supportedLocales' => $supported,
            'laravellocalization.localesOrder' => $languages->pluck('code')->toArray(),
        ]);
    }

    /**
     * The "active currency" for the current visitor: session override (from the currency
     * switcher) > company's home currency (when browsing as an employer) > site default.
     * Bound as a singleton so `currency()` helper and Blade views share one resolved instance
     * instead of re-querying per call.
     */
    protected function bindActiveCurrency(): void
    {
        $this->app->singleton('currency.active', function () {
            if (! Schema::hasTable('currencies')) {
                return null;
            }

            if ($code = session('currency_code')) {
                $currency = Currency::where('code', $code)->where('status', true)->first();
                if ($currency) {
                    return $currency;
                }
            }

            return Currency::where('is_default', true)->first()
                ?? Currency::where('status', true)->first();
        });
    }
}
