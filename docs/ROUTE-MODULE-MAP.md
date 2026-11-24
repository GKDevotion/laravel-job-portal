# Route and module map – Batch 01

| Route name | URI | Purpose | Status |
|---|---|---|---|
| `home` | `/` | Public landing page | Implemented |
| `jobs.index` | `/jobs` | Keyword/location search and pagination | Implemented |
| `jobs.show` | `/jobs/{slug}` | Job details | Implemented |
| `login` | `/login` | Login entry point | Placeholder |
| `register` | `/register` | Candidate registration entry point | Placeholder |
| `employer.register` | `/employer/register` | Employer registration entry point | Placeholder |

## Data model foundation
- `job_categories` 1-to-many `jobs`
- `companies` 1-to-many `jobs`
- Jobs reference one company and optionally one category.
- Job status defaults to `draft`; public queries show only `published` and unexpired records.

## Suggested next implementation order
1. Authentication, candidate/employer account registration, email verification and password reset.
2. Role/permission design and protected dashboard route groups.
3. Candidate profile and resume data model.
4. Employer company profile and ownership model.
5. Employer job CRUD, moderation and publication workflow.
6. Candidate job applications and application status history.
