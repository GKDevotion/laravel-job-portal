<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\File;

class TranslationToolService
{
    /**
     * All strings ever wrapped in __('...') across the front theme and admin panel, pulled from
     * the base (English) translation file if one exists, PLUS every key already translated in
     * any other locale — so a string added only in French still shows up as a row when editing
     * English, instead of silently disappearing from the tool.
     */
    public function stringsForLocale(string $locale): array
    {
        $allKeys = [];

        foreach (Language::pluck('code') as $code) {
            $allKeys = array_merge($allKeys, array_keys($this->readFile($code)));
        }

        $current = $this->readFile($locale);
        $result = [];

        foreach (array_unique($allKeys) as $key) {
            $result[$key] = $current[$key] ?? '';
        }

        ksort($result);

        return $result;
    }

    public function save(string $locale, array $translations): void
    {
        // Drop empty values so untranslated strings fall back to the key itself (Laravel's
        // default __() behavior) instead of persisting as literal empty strings.
        $filtered = array_filter($translations, fn ($value) => trim((string) $value) !== '');

        ksort($filtered);

        File::put(
            $this->pathFor($locale),
            json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    protected function readFile(string $locale): array
    {
        $path = $this->pathFor($locale);

        if (! File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true) ?: [];
    }

    protected function pathFor(string $locale): string
    {
        return lang_path("{$locale}.json");
    }
}
