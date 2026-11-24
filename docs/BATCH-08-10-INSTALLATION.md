# JobStock Batch 08–10 – Installation

This is a changed-files patch for the existing Laravel 13 JobStock project. It assumes Batch 05–07 is installed and its 16-test suite is passing.

## Included
- Batch 08: candidate job applications, duplicate-application protection, resume/cover letter submission, application list and withdrawal while status is `submitted`.
- Batch 09: save/unsave published jobs and candidate saved-jobs list.
- Batch 10: employer applicant inbox, status changes (`reviewing`, `shortlisted`, `interview`, `rejected`, `hired`) and private employer notes.

## Install
1. Back up the project and database.
2. Copy the folders/files in this ZIP into the matching locations in your Laravel project, preserving existing files unless the patch explicitly replaces them.
3. Open `routes/web.php` and insert the contents of `routes-web-additions.php.txt` **before** the existing `Route::fallback(...)` line. Do not replace the rest of your routes file.
4. In `resources/views/layouts/partials/header.blade.php`, add the following links to the candidate navigation list:
   - `route('candidate.applications.index')` labelled `My Applications`
   - `route('candidate.saved-jobs.index')` labelled `Saved Jobs`
   Add `route('employer.applications.index')` labelled `Applicants` to the employer navigation list.
5. Run:
   ```bash
   php artisan migrate
   php artisan storage:link
   php artisan optimize:clear
   php artisan test
   ```

## Important
- Do not run `migrate:fresh`; it can erase existing data.
- The migration adds `job_applications` and `saved_jobs`; it does not modify or delete existing tables.
- Candidate resume files are stored on Laravel's `public` disk. For production, consider making applicant resumes private and serving them through an authorized download controller. This initial release uses the existing public storage convention from candidate profile uploads.
- Employer access is restricted by checking that each application belongs to a job owned by the employer's company.
- Candidates can apply only to published, non-expired jobs and only once per job.
6. The patch also updates `app/Http/Controllers/DashboardController.php` and `resources/views/dashboard/index.blade.php` so candidate/employer dashboard counts and quick links reflect the new modules.
