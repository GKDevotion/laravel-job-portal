# JobStock Batch 02–04 — Installation

This is an overlay package for the latest supplied JobStock Laravel 13 project. It does not include Laravel core, `vendor/`, or the existing public assets. Keep your existing project and copy/merge the paths in this ZIP into the matching project paths.

## Included
- Native Laravel registration, login, logout, password reset, and email verification workflows.
- Candidate and employer registration with separate profile records.
- User role/status fields and role middleware (`role`).
- Candidate and employer profile editing, including resume/photo uploads.
- Role-aware starter dashboards.
- Super-admin seeder and feature tests.

## Before installing
1. Back up the complete project and database.
2. Confirm the current database already has the Batch 01 `users`, `companies`, and `job_listings` tables.
3. Copy/merge the ZIP contents into the Laravel project root. Preserve `.env`, `vendor/`, `public/assets/`, and all unrelated files.
4. Ensure `bootstrap/app.php` and `routes/web.php` changes are merged; this package adds the `role` middleware alias and new routes.
5. Add the variables from `.env.jobstock.example` to your existing `.env`, using a unique strong administrator password. Configure real SMTP credentials before production.

## Run commands
```bash
php artisan optimize:clear
php artisan migrate
php artisan db:seed --class=JobStockAdminSeeder
php artisan storage:link
```
`storage:link` is required for public resume/avatar URLs. If your Apache subfolder setup cannot expose the symlink, configure the public storage path according to your Apache host rather than changing Laravel asset URLs.

No `npm install`, Node.js, Vite build, or `php artisan serve` is required. Continue using your existing Apache root `.htaccess` deployment and project URL.

## First administrator
Set `JOBSTOCK_SUPER_ADMIN_EMAIL`, `JOBSTOCK_SUPER_ADMIN_PASSWORD`, and optionally `JOBSTOCK_SUPER_ADMIN_NAME` in `.env`, then run the seeder. The seeder intentionally does not create a predictable default password.

## Email
Email verification and password reset use Laravel's notification system. With `MAIL_MAILER=log`, messages are written to `storage/logs/laravel.log` for local testing. Configure SMTP for real delivery. The registration flow requires email verification before dashboard access.

## Routes
- `GET/POST /login`
- `GET/POST /register` (candidate)
- `GET/POST /employer/register`
- `GET/POST /forgot-password`
- `GET/POST /reset-password/{token}` and `/reset-password`
- `GET /email/verify`, signed verification URL, resend notification
- `GET /dashboard`
- `GET/PUT /candidate/profile`
- `GET/PUT /employer/profile`

## Notes / boundaries
- This batch creates the account, role, profile, and dashboard foundation. It does not yet implement job applications, saved jobs, applicant management, subscriptions, or admin CRUD screens.
- Candidate and employer accounts are public registrations. Admin and super-admin accounts must be created through the seeder or a trusted administrative process.
- Role middleware checks both role and `status=active`.
- Resume uploads accept PDF/DOC/DOCX up to 5 MB; avatar uploads accept image files up to 2 MB.
- Test the migrations against a database backup first. Do not use `migrate:fresh` on a database containing user data.
