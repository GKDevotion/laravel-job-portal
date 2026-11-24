<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobStockDemoSeeder extends Seeder
{
    public function run(): void
    {
        $categoryNames = [
            'Technology' => 'bi bi-cpu', 'Marketing' => 'bi bi-megaphone',
            'Finance' => 'bi bi-cash-stack', 'Healthcare' => 'bi bi-heart-pulse',
            'Design' => 'bi bi-palette', 'Education' => 'bi bi-mortarboard',
            'Customer Service' => 'bi bi-headset', 'Human Resources' => 'bi bi-people',
        ];

        foreach ($categoryNames as $name => $icon) {
            JobCategory::updateOrCreate(['slug' => (string) str($name)->slug()], [
                'name' => $name, 'icon' => $icon, 'is_active' => true,
            ]);
        }

        $companies = [
            ['name' => 'Northstar Digital', 'slug' => 'northstar-digital', 'location' => 'Remote'],
            ['name' => 'Vertex Solutions', 'slug' => 'vertex-solutions', 'location' => 'New York, USA'],
            ['name' => 'BluePeak Health', 'slug' => 'bluepeak-health', 'location' => 'London, UK'],
        ];

        foreach ($companies as $companyData) {
            $company = Company::updateOrCreate(['slug' => $companyData['slug']], $companyData + ['is_active' => true]);
            $samples = [
                ['title' => 'Senior Product Designer', 'category' => 'Design', 'type' => 'Full Time', 'place' => 'Remote', 'featured' => true],
                ['title' => 'Laravel Backend Developer', 'category' => 'Technology', 'type' => 'Full Time', 'place' => 'Ahmedabad, India', 'featured' => true],
                ['title' => 'Digital Marketing Specialist', 'category' => 'Marketing', 'type' => 'Contract', 'place' => 'Hybrid', 'featured' => false],
            ];
            foreach ($samples as $i => $sample) {
                $slug = (string) str($sample['title'].' '.$company->slug)->slug();
                Job::updateOrCreate(['slug' => $slug], [
                    'company_id' => $company->id,
                    'category_id' => JobCategory::where('name', $sample['category'])->value('id'),
                    'title' => $sample['title'], 'location' => $sample['place'],
                    'job_type' => $sample['type'], 'workplace_type' => $sample['place'] === 'Remote' ? 'Remote' : 'Hybrid',
                    'experience_level' => 'Mid-level', 'salary_min' => 45000 + ($i * 5000),
                    'salary_max' => 75000 + ($i * 5000), 'salary_currency' => 'USD',
                    'description' => 'Demo listing for initial interface testing. Replace this content with verified employer job information.',
                    'is_featured' => $sample['featured'], 'status' => 'published', 'published_at' => now()->subDays($i),
                ]);
            }
        }
    }
}
