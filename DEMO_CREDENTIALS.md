# Demo Data & Test Credentials

Run this once your `.env`/migrations are set up (see README.md for the full setup steps):

```bash
php artisan migrate
php artisan db:seed
php artisan db:seed --class="Database\Seeders\DemoDataSeeder"
```

> **Shell note:** the class name must be quoted with single backslashes as shown above. If
> you're on PowerShell use single quotes instead: `--class='Database\Seeders\DemoDataSeeder'`.
> If you still get "Target class ... does not exist" after quoting it correctly, run
> `composer dump-autoload` first — this happens if your Composer install used
> `--optimize-autoloader`, which caches a static classmap that doesn't notice new seeder files
> until regenerated.

The base `db:seed` creates roles/permissions, languages, currencies, job taxonomy (categories/
types/skills), pricing plans, and the super admin. `DemoDataSeeder` is separate on purpose —
it's realistic test data, not something you'd want in a production seed — and depends on the
base seed having already run.

**Password for every demo account below: `Password123!`**

## Admin
| Email | Role |
|---|---|
| `admin@jobportal.test` | Super Admin (password was set when you ran the Phase 1 `AdminSeeder` — `ChangeMe123!` unless you changed it) |

Login: `/admin/login`

## Companies (employers)
| Email | Company | State |
|---|---|---|
| `employer@nimbuscloud.test` | Nimbus Cloud Technologies | **Approved**, 12 job credits, has 2 employees |
| `hr@harborline.test` | Harborline Logistics | **Approved**, unlimited credits (untracked) |
| `careers@petalvine.test` | Petal & Vine Studio | **Pending approval** — 0 job credits once approved (use this one to test the admin approval flow *and* the "out of credits" block back to back) |

Login: `/en/employer/login`

## Employees (company team members)
| Email | Company | Role |
|---|---|---|
| `dana@nimbuscloud.test` | Nimbus Cloud | HR Manager (full access: post jobs, review applicants, manage team) |
| `marcus@nimbuscloud.test` | Nimbus Cloud | Recruiter (post jobs, review applicants — no team management) |
| `priya@harborline.test` | Harborline | Viewer (review applicants only) |

Login: `/en/employer/team/login`

## Job seekers
| Email | Notes |
|---|---|
| `alex.rivera@example.test` | Applied to "Senior Laravel Developer" — status: shortlisted |
| `sofia.marchetti@example.test` | Applied to "React Frontend Contractor" — status: rejected |
| `kwame.boateng@example.test` | Applied to 2 jobs — one submitted (untouched), one hired |
| `yuki.tanaka@example.test` | Applied to "Senior Laravel Developer" — status: interview |

Login: `/en/login`

## What's seeded and why — a walkthrough

**Jobs across every status** (all under Nimbus Cloud / Harborline):
- 3 **published** jobs, visible right now on `/en/jobs` (one is `is_featured`)
- 1 **pending_review** — log in as admin → `/admin/jobs` → approve or reject it
- 1 **draft** — never submitted, shows the draft state in the company's job list
- 1 **rejected** — has a rejection reason attached, shows how that displays to the employer
- 1 **closed** — a filled position, no longer accepting applications

**Applications across the full pipeline** — log in as `employer@nimbuscloud.test` (or the
`dana@nimbuscloud.test` employee) and open `/en/employer/applications` to see submitted,
shortlisted, interview, and hired all represented, plus one rejected application under
Harborline's second job.

> Applicant resumes point at placeholder file paths that don't actually exist on disk — the
> "Download Resume" link will 404. This is expected for seeded demo data; upload a real resume
> as a seeker (`/en/profile`) and apply to a job normally if you need a working download link
> to test against.

**Invoices** — one `paid` (Nimbus), one `unpaid` (Harborline). Log in as admin → `/admin/invoices`
to mark the unpaid one paid and watch Harborline's credit balance (or Nimbus's, if you use that
one) increase immediately. Log in as either company → `/en/employer/invoices` to view/download
the PDF.

## Suggested manual test path

1. **Admin approves a pending company**: `/admin/login` → `/admin/companies` → approve
   Petal & Vine Studio
2. **Admin moderates a job**: `/admin/jobs` → approve or reject the Harborline
   "Logistics Systems Analyst"
3. **Employer posts a new job**: log in as `employer@nimbuscloud.test` → `/en/employer/jobs/create`
   → fill it out → save draft → "Submit for Review" → confirm it now shows under `/admin/jobs`
4. **Run out of credits**: approve Petal & Vine (step 1), log in as `careers@petalvine.test`,
   try to submit a job for review → should be blocked and redirected to `/en/employer/pricing`
   with an explanatory message (their credits are seeded at 0)
5. **Buy credits**: from that pricing page, purchase "5 Job Posting Credits" → lands on a new
   unpaid invoice → log in as admin → `/admin/invoices` → mark it paid → log back in as
   Petal & Vine → credits balance is now 5 → job submission works
6. **Apply as a job seeker**: log in as `alex.rivera@example.test` → `/en/jobs` → apply to the
   "React Frontend Contractor" listing (upload any PDF as the resume) → confirm it appears
   under `/en/employer/applications` for Nimbus
7. **Move an applicant through the pipeline**: as `dana@nimbuscloud.test`, open Alex's new
   application → change status to "shortlisted" → "interview" → confirm the seeker sees the
   updated status at `/en/applications`
8. **Language + currency switching**: on any public page, switch the language dropdown to
   Arabic — confirm the layout flips to RTL and nav strings translate. Switch currency — confirm
   salary figures on `/en/jobs` recalculate.
9. **Translation tool**: `/admin/settings/translations?locale=fr` → change a string → refresh
   the French site → confirm it changed
10. **SEO check**: view source on any published job's detail page → confirm the
    `application/ld+json` `JobPosting` block is present with real data → visit `/sitemap.xml`
    → confirm the 3 published jobs and 2 approved companies are listed with hreflang alternates
