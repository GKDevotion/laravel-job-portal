# Job Portal — Phase 1: Foundation (Multi-Auth + DB Schema)

This is a **real Laravel project skeleton**, hand-built to match what `laravel new` + the required
packages produce. It was written outside a live Composer environment, so you need to run one
install step locally before it boots.

## What's in Phase 1

- **4 auth guards**, each with its own table, model, and password-reset flow:
  - `admin` → `admins` table → platform owner/staff
  - `company` → `companies` table → employers
  - `web` (default) → `users` table → job seekers
  - `employee` → `employees` table → company staff (HR/recruiters), scoped to one `company_id`
- **Roles & permissions** per guard via `spatie/laravel-permission` (e.g. Super Admin vs
  Moderator on the admin side; Owner on the company side; HR Manager / Recruiter / Viewer on
  the employee side).
- **Core job schema**: `job_categories`, `job_types`, `skills`, `job_listings` (translatable
  JSON columns for title/description so each job can be authored per-locale), `job_applications`,
  `saved_jobs`, `company_followers`.
- **Language & currency tables** ready for the multi-language/multi-currency system in Phase 3.
- **Minimal `invoices` table** (Phase 5 will build out numbers, PDF generation, line items).

## Getting it running

1. **Turn this into a full Laravel app.** Easiest path — create a fresh Laravel 11 project, then
   copy these files over it (they replace/extend the defaults, they don't stand alone):
   ```bash
   composer create-project laravel/laravel job-portal
   cd job-portal
   # now copy in: composer.json, config/auth.php, bootstrap/app.php,
   # app/Models/*, database/migrations/*, database/seeders/*, routes/*, .env.example
   composer update
   ```

2. **Install the extra packages** (already listed in the provided `composer.json`, so `composer
   update` above pulls them — but individually they are):
   ```bash
   composer require spatie/laravel-permission spatie/laravel-medialibrary \
     spatie/laravel-translatable spatie/laravel-sitemap mcamara/laravel-localization \
     league/flysystem-aws-s3-v3 barryvdh/laravel-dompdf intervention/image
   ```

3. **Publish package migrations/config** (media library needs its own migration):
   ```bash
   php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   ```

4. **Set up `.env`**, copy from `.env.example`, then:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run migrations + seeders**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
   This creates the roles/permissions, base languages (English, Arabic, French, Hindi — Arabic
   flagged `is_rtl`), base currencies (USD, EUR, INR, GBP), and a default super admin:
   ```
   admin@jobportal.test / ChangeMe123!
   ```
   **Change that password immediately** — it's a seeded placeholder, not meant for production.

## Why routes are commented out

`routes/admin.php`, `routes/company.php`, `routes/employee.php`, and `routes/web.php` currently
have their `Route::` lines commented — they show the exact shape (guard middleware, permission
middleware, resource routes) but reference controllers that don't exist yet. **Phase 2** builds
the controllers and uncomments these as we go, so nothing here 500s on a missing class.

## Multi-auth design notes

- Each actor type is a **separate table and model** (not a single `users` table with a `type`
  column). This keeps password resets, email verification, and permission sets cleanly isolated
  per guard — a company suspending an employee doesn't touch the `users`/job-seeker table at all.
- `Employee` always belongs to exactly one `company_id`. Every employee-side controller will
  scope queries to `auth('employee')->user()->company_id` — this is enforced in Phase 2, not just
  assumed.
- Job listings, applications, etc. use normal `belongsTo(Company::class)` / `belongsTo(User::class)`
  — only the **auth guard** differs from a typical single-table Laravel app.

## Next: Phase 2

Controllers + views for: job posting/editing, browsing/search/filter, the apply flow (resume
upload + cover letter), and the employer's applicant pipeline (submitted → shortlisted →
interview → hired/rejected).

---

# Phase 2: Controllers, Views, and the Apply/Pipeline Flow

Everything below is now wired to real routes and real controllers (not stubs).

## What's new in Phase 2

**Public site**
- `Front\HomeController` — homepage with featured/latest jobs, categories, top companies
- `Front\JobListingController` — search (`q`), filters (category, type, work mode, experience,
  country, min salary), sort (newest / salary high / salary low), pagination
- `Front\CompanyController` — public company directory + profile page with their open jobs

**Job seeker (`web` guard)**
- Register/login/logout (`Seeker\AuthController`)
- Dashboard with application/saved-job counts (`Seeker\DashboardController`)
- **Apply flow** (`Seeker\ApplicationController::store`) — upload a resume OR reuse the one
  already on their profile, optional cover letter, blocks duplicate applications to the same
  job, increments the job's `applications_count`
- Withdraw an application (blocked once it's `hired` or `rejected`)
- Save/unsave jobs (`Seeker\SavedJobController`)

**Employer (`company` guard)**
- Register (starts `approval_status = pending` — can't publish jobs until an admin approves,
  enforced in the controller, not just the UI)
- Job CRUD with a real status workflow: `draft` → `pending_review` → `published`. **Editing a
  published job automatically sends it back to `pending_review`** — a company can never silently
  change a live listing (see `JobListingService::update`).
- Applicant pipeline: `submitted → viewed → shortlisted → interview → offered → hired/rejected`,
  with employer-only internal notes per applicant
- Team management: invite employees by email (they get a password-set link scoped to the
  `employees` password broker), assign them a role (HR Manager / Recruiter / Viewer), suspend
  or remove them

**Employee (`employee` guard)**
- Everything on the employer side is mirrored here, but **hard-scoped to the employee's own
  `company_id`** at the query level (`JobListing::where('company_id', $employee->company_id)`)
  — not just permission-gated. Even a bug in the permission check couldn't leak another
  company's data, because the query never looks outside their own company to begin with.
- Permissions (`post jobs`, `edit jobs`, `review applications`, `manage team`) come from the
  employee's assigned role, seeded in Phase 1's `RolesPermissionsSeeder`.

**Shared logic**
- `App\Services\JobListingService` — both `Company\JobListingController` and
  `Employee\JobListingController` call this instead of duplicating create/update/publish logic.
- `App\Policies\JobListingPolicy` and `JobApplicationPolicy` — since the "actor" can be either
  a `Company` or an `Employee` model depending on which guard is logged in, controllers call
  `Gate::forUser($actor)->authorize(...)` explicitly rather than relying on the default guard.

## Views

All views use Tailwind (CDN, for now — Phase 6 swaps this for a proper build + the admin theme
system) and respect `dir="rtl"` automatically based on the active locale. Two layouts:
- `layouts.app` — public site + job seeker pages
- `layouts.dashboard` — shared employer/employee dashboard shell (sidebar nav adapts based on
  which guard is logged in)

Company and Employee job-posting forms **share one partial** (`company/jobs/_form.blade.php`)
so the two roles never drift out of sync on what fields exist.

## Try it locally

Once you've followed the Phase 1 setup steps (composer install, migrate, seed):

1. Visit `/employer/register` → create a company → you'll land on `/employer/dashboard`
   with a "pending approval" banner (no admin panel yet to approve it — Phase 6 adds that;
   for now you can flip it manually: `php artisan tinker` → `App\Models\Company::first()->update(['approval_status' => 'approved'])`)
2. Post a job from `/employer/jobs/create`, then hit "Submit for Review" on the edit screen
   (again, manually flip `status` to `published` via tinker until the admin panel exists)
3. Visit `/register` as a job seeker, browse `/jobs`, apply to the job you just published
4. Back in `/employer/applications`, move the applicant through the pipeline

## Next: Phase 3

Multi-language and multi-currency in practice — locale switching middleware, `mcamara/laravel-localization`
route groups, translating the admin-managed content (categories, job types), and a currency
switcher that actually changes displayed prices site-wide.

---

# Phase 3: Multi-Language + Multi-Currency (Working)

## What's new

**Multi-language**
- Every URL is now locale-prefixed: `/en/jobs`, `/ar/jobs`, `/fr/jobs`, `/hi/jobs` (see the
  `Route::group(['prefix' => LaravelLocalization::setLocale(), ...])` wrapper in `routes/web.php`
  — everything, including `admin.php`/`company.php`/`employee.php`, lives inside it).
- The **`languages` DB table is the real source of truth**, not a static config array.
  `AppServiceProvider::loadLocalesFromDatabase()` rewrites `laravellocalization.supportedLocales`
  from the DB on every boot, so adding a language in the (Phase 6) admin panel needs zero code
  changes or deploys. `config/laravellocalization.php` still ships a static fallback matching the
  Phase 1 seed data, so the app boots correctly even before that table is queried.
- RTL is **data-driven**: `current_language()->is_rtl` (from the `languages` table) sets
  `dir="rtl"` on `<html>` in both layouts — Arabic flips automatically, no hardcoded locale list.
- A language switcher (`resources/views/partials/language-currency-switcher.blade.php`) is in
  both the public nav and the employer/employee dashboard sidebar. It swaps the locale prefix
  on the *current* page via `LaravelLocalization::getLocalizedURL()`, so switching language from
  a job detail page keeps you on that same job.
- Sample translations shipped for Arabic, French, and Hindi (`lang/ar.json`, `lang/fr.json`,
  `lang/hi.json`) covering the nav, homepage, and job-detail strings — enough to see the whole
  flow working end to end. The full front theme + admin panel translation tool is Phase 6.
- Job categories, job types, and job listings were already built as translatable JSON columns
  in Phase 1/2 (`spatie/laravel-translatable`) — `$category->name` now genuinely returns the
  right language automatically based on the active locale, no extra work needed here.

**Multi-currency**
- `App\Http\Middleware\SetActiveCurrency` reads `?currency=USD` (or whichever code) from the
  query string and persists it in session — the currency switcher dropdown is just a `<select>`
  that submits itself via `onchange`.
- `active_currency()` / `money($amount)` are global helpers (autoloaded via `composer.json`'s
  `autoload.files`). **All monetary values in the DB are stored in the base currency** (the
  `currencies` row with `is_default = true`, `exchange_rate = 1`); `money()` is the single place
  conversion happens, using `Currency::format()` from Phase 1. Job listing prices on the browse
  and detail pages now call `money($job->salary_min)` instead of hardcoding the poster's currency
  — so switching currency in the nav actually changes every price on the page, site-wide.
- `App\Services\LocalizationSettingsService` — `setDefaultCurrency()` enforces that exactly one
  currency has `is_default = true` (and forces its rate to exactly 1, since everything else is
  relative to it). The Phase 6 admin panel's currency management screen will call this instead of
  updating the model directly, so that invariant can never be broken from the UI.

## New/changed files
- `config/laravellocalization.php` — supported locales (static fallback), URL behavior
- `app/Providers/AppServiceProvider.php` — DB-driven locale config + active-currency binding
- `app/Http/Middleware/SetActiveCurrency.php`
- `app/Support/helpers.php` — `money()`, `active_currency()`, `current_language()`
- `app/Services/LocalizationSettingsService.php`
- `resources/views/partials/language-currency-switcher.blade.php`
- `lang/ar.json`, `lang/fr.json`, `lang/hi.json`
- `routes/web.php` — now wraps everything in the locale route group
- `bootstrap/app.php` — registers the localization middleware aliases + global currency middleware

## Extra setup step for this phase

```bash
composer require mcamara/laravel-localization
php artisan vendor:publish --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"
```

## Try it locally
1. Visit `/en/jobs`, then use the language dropdown top-right to switch to `/ar/jobs` — the
   whole page should flip to RTL and the nav/homepage strings translate.
2. Switch the currency dropdown next to it — job salary ranges on `/en/jobs` and any job detail
   page recalculate immediately using the `exchange_rate` seeded in Phase 1 (USD, EUR, INR, GBP).
3. `php artisan tinker` → `App\Models\Language::create([...])` with a new code/locale/is_rtl,
   flip `status = true` — refresh any page and it appears in the switcher immediately, no cache
   clear needed (the DB read happens on every boot until Phase 6 adds the admin-managed cache
   layer via `LocalizationSettingsService`).

## One caveat worth knowing

`mcamara/laravel-localization`'s locale-prefix-as-route-group pattern doesn't play well with
`php artisan route:cache` — the prefix gets baked in from whatever locale was active *when the
cache was built*. Don't route-cache this app in production; the package's own `route:trans:cache`
alternative (or simply skipping route caching) is the standard workaround, and it's a fine
tradeoff at job-portal traffic scale.

## Next: Phase 4

The media system — local storage today (avatars, resumes, logos already use `Storage::disk('public')`
via `spatie/laravel-medialibrary`), extended to support Amazon S3 and DigitalOcean Spaces as
drop-in alternatives via the `AWS_*` / `AWS_ENDPOINT` env vars already stubbed in `.env.example`.

---

# Phase 4: Media System (Local, S3, DigitalOcean Spaces)

## What's new

- **One env var switches storage backends.** `FILESYSTEM_DISK=public` (default, local disk,
  served via `/storage`) or `FILESYSTEM_DISK=s3` — and `media-library.php`'s `disk_name` follows
  whichever one is set, so logos/avatars/covers move to S3 automatically with zero code changes.
- **DigitalOcean Spaces is the same `s3` disk**, just with `AWS_ENDPOINT` set to your Spaces
  region (e.g. `https://nyc3.digitaloceanspaces.com`) and `AWS_USE_PATH_STYLE_ENDPOINT=false`.
  Spaces is S3-API-compatible, so this needed no separate driver — see `config/filesystems.php`.
- **Company** now has real media collections: `logo` and `cover_image`, both `singleFile()` so
  uploading a replacement automatically deletes the old one wherever it lives (local or cloud) —
  no orphaned files. Conversions: `thumb` (200×200 crop) for the logo, `cover` (1200×400 crop)
  for the banner, generated synchronously on upload.
- **User (job seeker)** now has an `avatar` collection (150×150 thumb conversion) and a `resume`
  collection replacing the old plain `resume` string column from Phase 1 — the seeker's default
  resume is now managed the same way as every other file in the app. `avatarUrl()` falls back to
  a generated initials avatar so templates never need a null check.
- **The apply flow was updated**: reusing your profile resume now streams a copy of the media-
  library file into the application's own resume slot (works identically on local or S3 — see
  `Seeker\ApplicationController::store`), rather than assuming a local file path.
- New controllers: `Seeker\ProfileController` (avatar, resume, bio/contact fields) and
  `Company\ProfileController` (logo, cover image, company details) — both with matching views
  and sidebar/nav links.
- `database/migrations/2025_01_04_000001_create_media_table.php` — media library's own table,
  included directly since this sandbox can't run `vendor:publish`.

## New/changed files
- `config/filesystems.php` — `public` and `s3` disks (Spaces = `s3` + `AWS_ENDPOINT`)
- `config/media-library.php` — disk selection, conversion settings
- `database/migrations/..._create_media_table.php`
- `app/Models/Company.php`, `app/Models/User.php` — media collections/conversions
- `app/Http/Controllers/Seeker/ProfileController.php`, `Company/ProfileController.php`
- `resources/views/seeker/profile/edit.blade.php`, `company/profile/edit.blade.php`
- `resources/views/company/applications/show.blade.php`, `employee/applications/show.blade.php`
  — resume download links now read from the explicitly configured disk

## Switching from local storage to S3/Spaces later

If you launch on local storage and want to migrate to S3/Spaces afterward, existing files are
**not** moved automatically — only new uploads go to the new disk once you flip `FILESYSTEM_DISK`.
To migrate existing media:
```bash
php artisan tinker
>>> Spatie\MediaLibrary\MediaCollections\Models\Media::all()->each(fn ($m) => $m->move($m->model, $m->collection_name, 's3'));
```
Run that once after setting your `AWS_*` credentials, before switching `FILESYSTEM_DISK` to `s3`
in production.

## Try it locally
1. `php artisan storage:link` (creates the `public/storage` symlink for local disk serving)
2. Log in as a job seeker → `/profile` → upload an avatar and a resume PDF
3. Log in as an employer → `/employer/profile` → upload a logo and cover image
4. Apply to a job using "use resume from my profile" — check the applicant's resume link in the
   employer's applicant review screen; it should download the copied file correctly

## Next: Phase 5

Invoicing — no live payment gateway yet, just generating real invoice records and downloadable
PDF invoices (via `barryvdh/laravel-dompdf`, already in `composer.json`) tied to whatever
billable events you want to track (e.g. featured job listings, job posting credits).



