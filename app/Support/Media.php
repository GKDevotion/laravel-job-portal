<?php

namespace App\Support;

use Illuminate\Support\Str;

class Media
{
    /**
     * spatie/laravel-medialibrary builds URLs from the disk's configured 'url' value
     * (config/filesystems.php: env('APP_URL').'/storage' for the local 'public' disk). If
     * APP_URL is missing, empty, or stale in a cached config (common right after cloning the
     * project or editing .env without running `php artisan config:clear`), that concatenation
     * silently produces a root-relative path like "/storage/1/conversions/thumb.jpg" instead
     * of a full URL — which usually still *works* in a browser (resolved against the current
     * origin) but breaks the moment the image is used somewhere that isn't a same-origin page:
     * emails, the PDF invoice template, an API response, or a different subdomain.
     *
     * This wraps any media URL and guarantees it's absolute by falling back to Laravel's
     * request-aware url() helper — which uses the actual current request's host — rather than
     * depending solely on a static APP_URL env value being correctly configured.
     */
    public static function absoluteUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '//'])) {
            return $url;
        }

        return url($url);
    }
}
