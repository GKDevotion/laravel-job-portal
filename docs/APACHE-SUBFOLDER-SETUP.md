# JobStock – Apache/XAMPP subfolder deployment (no artisan serve)

This setup is for a Laravel project stored at a path such as:

`C:/xampp/htdocs/laravel/job-portal/`

and opened at:

`http://127.0.0.1/laravel/job-portal/`

## 1. File placement

- Copy the package's `.htaccess` into the Laravel project root, beside `artisan`, `app/`, `bootstrap/`, `public/`, and `vendor/`.
- Copy the package's `public/.htaccess` into the Laravel `public/` directory, replacing it only after keeping a backup.
- Do not move CSS, JS, images, or fonts out of `public/`.

## 2. Configure `.env`

```env
APP_URL=http://127.0.0.1/laravel/job-portal
ASSET_URL=
```

Keep the existing `APP_KEY` and database settings. Do not generate a new key on an existing installation.

## 3. Blade asset and URL rules

Use Laravel URL helpers in every Blade file:

```blade
<link rel="stylesheet" href="{{ asset('assets/css/jobstock-app.css') }}">
<script src="{{ asset('assets/js/jobstock-app.js') }}"></script>
<img src="{{ asset('assets/jobstock/img/logo.png') }}" alt="JobStock">
<a href="{{ route('jobs.index') }}">Find jobs</a>
```

Do not hardcode `/public/...`, `127.0.0.1`, or `/laravel/job-portal` in Blade templates. `asset()` and `route()` generate the subfolder-aware URLs using `APP_URL`.

With this root rewrite, the browser requests `/laravel/job-portal/assets/...`; Apache internally serves the corresponding file from `public/assets/...`.

## 4. Apache requirements

- Apache `mod_rewrite` must be enabled.
- The directory must allow `.htaccess` overrides (`AllowOverride All`).
- Restart Apache after changing Apache configuration.
- Do not run `php artisan serve`.

## 5. Clear Laravel cached configuration

Run these commands from the project directory using terminal/Command Prompt:

```bash
php artisan optimize:clear
```

This is a one-time maintenance command, not a server command. The website itself is served by Apache.

## 6. Verify

Open:

`http://127.0.0.1/laravel/job-portal/`

Then directly open:

`http://127.0.0.1/laravel/job-portal/assets/css/jobstock-app.css`

It should return CSS, not a Laravel 404 page. Also test `/jobs` and a job detail URL.

## Security note

The root `.htaccess` denies direct web access to Laravel source directories and sensitive dotfiles, and only routes public assets to `public/`. The strongest production configuration is still to set Apache's DocumentRoot / VirtualHost directly to the Laravel `public/` directory. The root rewrite is a compatibility approach for local XAMPP subfolder hosting.
