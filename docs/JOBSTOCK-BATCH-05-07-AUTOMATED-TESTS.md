# JobStock Batch 05–07 Automated Test Pack

This is an automated Laravel feature-test pack for the Batch 05–07 code in the
JobStock project ZIP and overlay package:

- Batch 05: categories, skills and locations
- Batch 06: employer job posting and ownership checks
- Batch 07: public job search and job detail visibility
- Admin moderation and role protection

## Files

- `tests/Feature/JobStockBatch0507Test.php`
- `run-jobstock-batch-05-07-tests.bat`
- `docs/JOBSTOCK-BATCH-05-07-AUTOMATED-TESTS.md`

## Install

Copy the `tests` folder into the Laravel project root and merge it with the
existing `tests` folder. Copy the `.bat` runner into the Laravel project root.
Copy the documentation file into `docs/`.

Do not replace your existing `phpunit.xml`; the supplied project already
configures PHPUnit to use SQLite `:memory:` for tests.

## Run on Windows / XAMPP

Double-click:

`run-jobstock-batch-05-07-tests.bat`

Or run from the project root:

```bash
php artisan test --testsuite=Feature --filter=JobStockBatch0507Test
```

Run the entire project suite:

```bash
php artisan test
```

## Safety

The existing `phpunit.xml` sets `DB_CONNECTION=sqlite` and
`DB_DATABASE=:memory:` for the test environment. The test class uses
`RefreshDatabase`, so test migrations and records are created in the isolated
in-memory test database, not in the normal MySQL `.env` database.

Before running, verify that your local `phpunit.xml` still contains those
testing environment values. Do not run this suite if you have changed the
testing DB configuration to point at your live database.

No npm packages, frontend build, `artisan serve`, or schema changes are needed
to install this test pack. Your Laravel dependencies must already be installed
(`vendor/autoload.php` must exist).

## Covered scenarios

1. Public job listing loads and hides pending jobs.
2. Keyword and location filters work together.
3. Public job details are available for published jobs only.
4. Employer can open their job manager and submit a job for review.
5. Employer cannot edit another company's job.
6. Invalid salary range is rejected.
7. Admin can approve a pending job and make it public.
8. Candidate cannot access admin category/review routes.
9. Admin can create a category; a category in use cannot be deleted.
10. Inactive categories are not shown in public search filters.

## Important limitation

These are application-level feature tests. They do not validate SMTP delivery,
real browser rendering, Apache rewrite configuration, or actual uploaded-file
permissions. Those require separate environment/browser checks.
