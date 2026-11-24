# Batch 05–07 Test Checklist

- [ ] Run migrations on a backup database first.
- [ ] Admin can create and update job categories.
- [ ] Category with assigned jobs cannot be deleted; deactivate instead.
- [ ] Employer without company profile is blocked from job management with a clear 403 message.
- [ ] Employer can create a job and it receives `pending` status.
- [ ] Employer can only edit/delete jobs owned by their company.
- [ ] Employer cannot edit or delete published jobs in this release.
- [ ] Admin can approve and reject jobs.
- [ ] Approved jobs appear on `/jobs`; pending/rejected/draft jobs do not.
- [ ] Expired jobs are excluded from public search.
- [ ] Search keyword matches title, description, company and skill.
- [ ] Location, category, job type, workplace, experience and salary filters work together.
- [ ] Sorting by latest/highest salary/lowest salary preserves filters through pagination.
- [ ] Test desktop, tablet and mobile layouts under Apache subfolder deployment.

- [ ] Run taxonomy seeder twice and confirm no duplicate skills or locations.
