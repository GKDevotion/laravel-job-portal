<?php

return [

    'default-locale-key' => 'default_locale',

    // NOTE: this array is the package's static fallback list. The actual source of truth
    // for which locales are enabled is the `languages` DB table (managed in the admin panel,
    // Phase 6) — AppServiceProvider::boot() overwrites this config at runtime from that table
    // so admins never have to touch code to add a language. This static version just needs to
    // exist so the package boots before the DB is queried, and matches the seeder from Phase 1.
    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_US',
        ],
        'ar' => [
            'name' => 'Arabic',
            'script' => 'Arab',
            'native' => 'العربية',
            'regional' => 'ar_SA',
        ],
        'fr' => [
            'name' => 'French',
            'script' => 'Latn',
            'native' => 'Français',
            'regional' => 'fr_FR',
        ],
        'hi' => [
            'name' => 'Hindi',
            'script' => 'Deva',
            'native' => 'हिन्दी',
            'regional' => 'hi_IN',
        ],
    ],

    'useAcceptLanguageHeader' => true,

    'hideDefaultLocaleInURL' => false, // keep /en/jobs explicit — avoids ambiguous root-locale bugs

    'localesOrder' => ['en', 'ar', 'fr', 'hi'],

    'localesMapping' => [],

    'utf8suffix' => '.UTF-8',

    'urlsIgnored' => [
        '/admin*',        // admin panel manages its own locale via the translation tool (Phase 6)
        '/sitemap.xml',
        '/up',
    ],
];
