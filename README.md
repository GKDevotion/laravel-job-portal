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
