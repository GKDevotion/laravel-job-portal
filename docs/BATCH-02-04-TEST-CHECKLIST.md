# Batch 02–04 Verification Checklist

- [ ] Existing homepage, job listing, and job details still load under Apache subfolder URL.
- [ ] Candidate can register; candidate profile row is created.
- [ ] Employer can register; company and employer profile rows are created.
- [ ] Duplicate email is rejected.
- [ ] Invalid password confirmation is rejected.
- [ ] Login accepts valid credentials and regenerates session.
- [ ] Inactive account cannot log in.
- [ ] Unverified account is directed to email verification notice.
- [ ] Verification link grants dashboard access.
- [ ] Password reset email is delivered/logged and reset token works.
- [ ] Candidate cannot access employer profile routes.
- [ ] Employer cannot access candidate profile routes.
- [ ] Admin seeder creates configured super admin.
- [ ] Candidate profile can save skills, links, resume and avatar.
- [ ] Employer profile can save company details.
- [ ] Uploaded files are stored on the public disk and served through configured storage URL.
- [ ] Run `php artisan test` and review failures before production deployment.
