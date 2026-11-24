# JobStock Batch 05–07 Installation

This is an overlay package for the current Laravel 13 JobStock project. It extends the existing `job_categories`, `companies`, and `job_listings` schema; it does not recreate those tables.

## Included
- Batch 05: category administration plus skills and job-location taxonomy tables.
- Batch 06: employer-owned job create/edit/delete workflow and admin approval/rejection queue.
- Batch 07: keyword/skill, location, category, job type, workplace, experience, minimum salary and sort filters.

## Install
1. Back up the project and database.
2. Extract this ZIP into the Laravel project root, preserving folder paths.
3. Run `php artisan optimize:clear`.
4. Run `php artisan migrate`.
5. Run `php artisan db:seed --class=JobStockTaxonomySeeder` to load starter skills and Indian city locations. This seeder is safe to rerun.
6. Ensure an employer has an `employer_profiles` row linked to an active `companies` row before posting jobs.
7. Ensure the admin account has role `admin` or `super_admin` and is email-verified.

No npm, Node.js, additional Composer package, or `artisan serve` is required. Bootstrap 5 and existing public assets are reused.

## URLs
- Public job search: `/jobs`
- Employer job manager: `/employer/jobs`
- Admin category management: `/admin/categories`
- Admin job review: `/admin/job-reviews`

## Workflow
Employer submits a job => status `pending`; admin approves => status `published` and `published_at` is set; rejected jobs remain private and can be edited/resubmitted. Public search only returns published, non-expired jobs.

## Notes
- The existing schema uses `job_listings` as the physical table for the `Job` model.
- Candidate applications/saved jobs are not included in this batch; they belong in the next workflow milestone.
- Seed/import skills and locations through your future admin data-management screen or direct seed data. The job form displays currently active skills.
