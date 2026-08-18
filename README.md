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

