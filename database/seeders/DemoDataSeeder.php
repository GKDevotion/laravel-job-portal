<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\JobType;
use App\Models\PricingPlan;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Populates the app with realistic, varied data across every status/state so the whole
 * system can be clicked through manually without hand-creating records first. Safe to
 * re-run: uses firstOrCreate keyed on unique fields throughout.
 *
 * Run with: php artisan db:seed --class=Database\\Seeders\\DemoDataSeeder
 * (after the base `php artisan db:seed` has run at least once, since this depends on
 * languages/currencies/roles/job taxonomy already existing)
 */
class DemoDataSeeder extends Seeder
{
    protected string $password = 'Password123!';

    public function run(): void
    {
        $companies = $this->seedCompanies();
        $this->seedEmployees($companies);
        $seekers = $this->seedSeekers();
        $jobs = $this->seedJobs($companies);
        $this->seedApplications($jobs, $seekers);
        $this->seedInvoices($companies);

        $this->command->info('Demo data seeded. See DEMO_CREDENTIALS.md for login details.');
    }

    protected function seedCompanies(): array
    {
        $currency = Currency::where('is_default', true)->first();

        $data = [
            [
                'company_name' => 'Nimbus Cloud Technologies',
                'email' => 'employer@nimbuscloud.test',
                'industry' => 'Information Technology',
                'team_size' => '51-200',
                'country' => 'United States',
                'city' => 'Austin',
                'about' => 'Nimbus Cloud Technologies builds infrastructure tooling for mid-market SaaS companies. We are fully remote-friendly and hire globally.',
                'website' => 'https://nimbuscloud.test',
                'approval_status' => 'approved',
                'is_verified' => true,
                'job_posting_credits' => 12,
            ],
            [
                'company_name' => 'Harborline Logistics',
                'email' => 'hr@harborline.test',
                'industry' => 'Transportation & Logistics',
                'team_size' => '201-500',
                'country' => 'United Kingdom',
                'city' => 'Manchester',
                'about' => 'Harborline Logistics moves freight across Europe. We are growing our operations and customer support teams.',
                'website' => 'https://harborline.test',
                'approval_status' => 'approved',
                'is_verified' => true,
                'job_posting_credits' => null, // unlimited/untracked — demonstrates that path too
            ],
            [
                'company_name' => 'Petal & Vine Studio',
                'email' => 'careers@petalvine.test',
                'industry' => 'Design',
                'team_size' => '1-10',
                'country' => 'Canada',
                'city' => 'Toronto',
                'about' => 'A small branding and design studio looking for our third designer.',
                'approval_status' => 'pending', // deliberately left pending — test the admin approval flow on this one
                'is_verified' => false,
                'job_posting_credits' => 0, // deliberately zero — test the "out of credits" block once approved
            ],
        ];

        $companies = [];
        foreach ($data as $row) {
            $company = Company::firstOrCreate(
                ['email' => $row['email']],
                array_merge($row, [
                    'slug' => Str::slug($row['company_name']),
                    'password' => $this->password,
                    'currency_id' => $currency?->id,
                ])
            );

            if (! $company->hasRole('Owner')) {
                $company->assignRole('Owner');
            }

            $companies[] = $company;
        }

        return $companies;
    }

    protected function seedEmployees(array $companies): void
    {
        [$nimbus, $harborline] = $companies;

        $employees = [
            ['company' => $nimbus, 'name' => 'Dana Whitfield', 'email' => 'dana@nimbuscloud.test', 'job_title' => 'Head of Talent', 'department' => 'hr', 'role' => 'HR Manager'],
            ['company' => $nimbus, 'name' => 'Marcus Chen', 'email' => 'marcus@nimbuscloud.test', 'job_title' => 'Technical Recruiter', 'department' => 'recruitment', 'role' => 'Recruiter'],
            ['company' => $harborline, 'name' => 'Priya Nair', 'email' => 'priya@harborline.test', 'job_title' => 'Recruitment Coordinator', 'department' => 'recruitment', 'role' => 'Viewer'],
        ];

        foreach ($employees as $row) {
            $employee = Employee::firstOrCreate(
                ['email' => $row['email']],
                [
                    'company_id' => $row['company']->id,
                    'name' => $row['name'],
                    'password' => $this->password,
                    'job_title' => $row['job_title'],
                    'department' => $row['department'],
                    'status' => true,
                ]
            );

            if (! $employee->hasRole($row['role'])) {
                $employee->assignRole($row['role']);
            }
        }
    }

    protected function seedSeekers(): array
    {
        $data = [
            ['name' => 'Alex Rivera', 'email' => 'alex.rivera@example.test', 'designation' => 'Backend Developer', 'experience_level' => 'mid', 'city' => 'Denver', 'country' => 'United States'],
            ['name' => 'Sofia Marchetti', 'email' => 'sofia.marchetti@example.test', 'designation' => 'Product Designer', 'experience_level' => 'senior', 'city' => 'Milan', 'country' => 'Italy'],
            ['name' => 'Kwame Boateng', 'email' => 'kwame.boateng@example.test', 'designation' => 'Customer Support Specialist', 'experience_level' => 'entry', 'city' => 'Accra', 'country' => 'Ghana'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki.tanaka@example.test', 'designation' => 'Full Stack Engineer', 'experience_level' => 'senior', 'city' => 'Osaka', 'country' => 'Japan'],
        ];

        $seekers = [];
        foreach ($data as $row) {
            $seekers[] = User::firstOrCreate(
                ['email' => $row['email']],
                array_merge($row, ['password' => $this->password, 'is_open_to_work' => true])
            );
        }

        return $seekers;
    }

    protected function seedJobs(array $companies): array
    {
        [$nimbus, $harborline, $petalVine] = $companies;

        $categoryIT = JobCategory::where('slug', 'it-software')->first();
        $categoryDesign = JobCategory::where('slug', 'design-creative')->first();
        $categoryCS = JobCategory::where('slug', 'customer-service')->first();
        $fullTime = JobType::where('slug', 'full-time')->first();
        $contract = JobType::where('slug', 'contract')->first();
        $currency = Currency::where('is_default', true)->first();

        $php = Skill::where('slug', 'php')->first();
        $laravel = Skill::where('slug', 'laravel')->first();
        $react = Skill::where('slug', 'react')->first();
        $figma = Skill::where('slug', 'figma')->first();

        $jobs = [];

        // --- Published, active jobs (visible on the public site right now) ---
        $jobs['published_1'] = $this->makeJob($nimbus, $categoryIT, $fullTime, $currency, [
            'title' => 'Senior Laravel Developer',
            'description' => "We're looking for a senior backend engineer to help scale our infrastructure platform. You'll work closely with our small, senior team on high-throughput services.",
            'requirements' => '5+ years PHP/Laravel experience. Comfortable with queues, caching, and database performance tuning.',
            'work_mode' => 'remote',
            'salary_min' => 95000, 'salary_max' => 130000,
            'experience_level' => 'senior',
            'status' => 'published', 'published_at' => now()->subDays(5),
            'is_featured' => true,
        ], [$php, $laravel]);

        $jobs['published_2'] = $this->makeJob($nimbus, $categoryIT, $contract, $currency, [
            'title' => 'React Frontend Contractor',
            'description' => 'Six-month contract to rebuild our internal admin dashboard in React. Possible extension.',
            'work_mode' => 'remote',
            'salary_min' => 60, 'salary_max' => 85, 'salary_period' => 'hourly',
            'experience_level' => 'mid',
            'status' => 'published', 'published_at' => now()->subDays(2),
        ], [$react]);

        $jobs['published_3'] = $this->makeJob($harborline, $categoryCS, $fullTime, $currency, [
            'title' => 'Customer Support Representative',
            'description' => 'Front-line support for our logistics customers via chat and email. Training provided.',
            'work_mode' => 'onsite',
            'city' => 'Manchester', 'country' => 'United Kingdom',
            'salary_min' => 26000, 'salary_max' => 30000,
            'experience_level' => 'entry',
            'status' => 'published', 'published_at' => now()->subDay(),
        ], []);

        // --- Pending admin review (tests the moderation queue) ---
        $jobs['pending_1'] = $this->makeJob($harborline, $categoryIT, $fullTime, $currency, [
            'title' => 'Logistics Systems Analyst',
            'description' => 'Analyze and improve our route-planning software. SQL-heavy role working with our ops team.',
            'work_mode' => 'hybrid',
            'city' => 'Manchester', 'country' => 'United Kingdom',
            'salary_min' => 45000, 'salary_max' => 58000,
            'experience_level' => 'mid',
            'status' => 'pending_review',
        ], []);

        // --- Draft (never submitted) ---
        $jobs['draft_1'] = $this->makeJob($nimbus, $categoryDesign, $fullTime, $currency, [
            'title' => 'Product Designer (Draft — Not Yet Submitted)',
            'description' => 'Placeholder draft to demonstrate the draft state in the company jobs list.',
            'work_mode' => 'remote',
            'status' => 'draft',
        ], [$figma]);

        // --- Rejected by admin (tests the rejection-reason display) ---
        $jobs['rejected_1'] = $this->makeJob($nimbus, $categoryIT, $fullTime, $currency, [
            'title' => 'Growth Marketer',
            'description' => 'Own our demand-gen funnel end to end.',
            'work_mode' => 'remote',
            'status' => 'rejected',
            'rejection_reason' => 'This role doesn\'t match your approved company category (Information Technology). Please contact support to update your account before posting marketing roles.',
        ], []);

        // --- Closed (past listing) ---
        $jobs['closed_1'] = $this->makeJob($harborline, $categoryCS, $fullTime, $currency, [
            'title' => 'Warehouse Operations Lead',
            'description' => 'This position has been filled.',
            'work_mode' => 'onsite',
            'city' => 'Manchester', 'country' => 'United Kingdom',
            'status' => 'closed', 'published_at' => now()->subMonths(2),
        ], []);

        return $jobs;
    }

    protected function makeJob(Company $company, ?JobCategory $category, ?JobType $type, ?Currency $currency, array $attrs, array $skills): JobListing
    {
        $title = $attrs['title'];

        $job = JobListing::firstOrCreate(
            ['slug' => Str::slug($title)],
            array_merge([
                'company_id' => $company->id,
                'job_category_id' => $category?->id,
                'job_type_id' => $type?->id,
                'currency_id' => $currency?->id,
                'title' => ['en' => $title],
                'description' => ['en' => $attrs['description']],
                'requirements' => isset($attrs['requirements']) ? ['en' => $attrs['requirements']] : null,
                'salary_period' => $attrs['salary_period'] ?? 'yearly',
                'vacancies' => 1,
                'is_featured' => false,
            ], $attrs, ['title' => ['en' => $title], 'description' => ['en' => $attrs['description']]])
        );

        if ($skills) {
            $job->skills()->sync(collect($skills)->filter()->pluck('id'));
        }

        return $job;
    }

    protected function seedApplications(array $jobs, array $seekers): void
    {
        [$alex, $sofia, $kwame, $yuki] = $seekers;

        $applications = [
            ['job' => $jobs['published_1'], 'user' => $alex, 'status' => 'shortlisted', 'cover_letter' => "I've spent the last 4 years building Laravel APIs at scale and would love to bring that experience to Nimbus."],
            ['job' => $jobs['published_1'], 'user' => $yuki, 'status' => 'interview', 'cover_letter' => 'Full stack background with heavy Laravel + React overlap with your stack.'],
            ['job' => $jobs['published_1'], 'user' => $kwame, 'status' => 'submitted', 'cover_letter' => null],
            ['job' => $jobs['published_2'], 'user' => $sofia, 'status' => 'rejected', 'cover_letter' => 'Applying as I have some React experience alongside my design background.'],
            ['job' => $jobs['published_3'], 'user' => $kwame, 'status' => 'hired', 'cover_letter' => 'I have 2 years of customer support experience and am excited about logistics.'],
        ];

        foreach ($applications as $row) {
            /** @var JobListing $job */
            $job = $row['job'];
            /** @var User $user */
            $user = $row['user'];

            if ($job->applications()->where('user_id', $user->id)->exists()) {
                continue;
            }

            // Simulate a resume file existing on disk without requiring a real upload —
            // download links will 404 gracefully if clicked (expected/documented for demo data).
            $resumePath = "resumes/demo/{$user->id}-resume.pdf";

            JobApplication::create([
                'job_listing_id' => $job->id,
                'user_id' => $user->id,
                'resume_path' => $resumePath,
                'cover_letter' => $row['cover_letter'],
                'status' => $row['status'],
                'status_updated_at' => now()->subDays(rand(0, 4)),
            ]);

            $job->increment('applications_count');
        }
    }

    protected function seedInvoices(array $companies): void
    {
        [$nimbus, $harborline] = $companies;
        $currency = Currency::where('is_default', true)->first();
        $creditsPlan = PricingPlan::where('type', 'job_credits')->first();

        if (! $creditsPlan) {
            return;
        }

        // One already paid (tests the "paid" invoice view + PDF download)
        $paid = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-DEMO-000001'],
            [
                'company_id' => $nimbus->id,
                'pricing_plan_id' => $creditsPlan->id,
                'description' => $creditsPlan->name,
                'amount' => $creditsPlan->price,
                'subtotal' => $creditsPlan->price,
                'currency_id' => $currency?->id,
                'billing_name' => $nimbus->company_name,
                'status' => 'paid',
                'paid_at' => now()->subDays(10),
                'payment_reference' => 'DEMO-BANK-TRANSFER-001',
                'due_date' => now()->subDays(17),
            ]
        );
        if ($paid->wasRecentlyCreated) {
            InvoiceItem::create(['invoice_id' => $paid->id, 'description' => $creditsPlan->name, 'quantity' => 1, 'unit_price' => $creditsPlan->price, 'amount' => $creditsPlan->price]);
        }

        // One unpaid (tests the admin "mark as paid" action)
        $unpaid = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-DEMO-000002'],
            [
                'company_id' => $harborline->id,
                'pricing_plan_id' => $creditsPlan->id,
                'description' => $creditsPlan->name,
                'amount' => $creditsPlan->price,
                'subtotal' => $creditsPlan->price,
                'currency_id' => $currency?->id,
                'billing_name' => $harborline->company_name,
                'status' => 'unpaid',
                'due_date' => now()->addDays(5),
            ]
        );
        if ($unpaid->wasRecentlyCreated) {
            InvoiceItem::create(['invoice_id' => $unpaid->id, 'description' => $creditsPlan->name, 'quantity' => 1, 'unit_price' => $creditsPlan->price, 'amount' => $creditsPlan->price]);
        }
    }
}
