<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedTypes();
        $this->seedSkills();
    }

    protected function seedCategories(): void
    {
        $categories = [
            'IT & Software', 'Design & Creative', 'Sales & Marketing', 'Customer Service',
            'Finance & Accounting', 'Human Resources', 'Engineering', 'Healthcare',
            'Education & Training', 'Writing & Content',
        ];

        foreach ($categories as $i => $name) {
            JobCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => ['en' => $name], 'status' => true, 'order' => $i + 1]
            );
        }
    }

    protected function seedTypes(): void
    {
        // slug is the stable machine-readable key — used to map to schema.org's JobPosting
        // employmentType enum in the front theme's structured data, since the display `name`
        // is translatable free text and shouldn't be parsed for that.
        $types = [
            ['slug' => 'full-time', 'name' => 'Full-time'],
            ['slug' => 'part-time', 'name' => 'Part-time'],
            ['slug' => 'contract', 'name' => 'Contract'],
            ['slug' => 'internship', 'name' => 'Internship'],
            ['slug' => 'temporary', 'name' => 'Temporary'],
            ['slug' => 'volunteer', 'name' => 'Volunteer'],
        ];

        foreach ($types as $type) {
            JobType::firstOrCreate(
                ['slug' => $type['slug']],
                ['name' => ['en' => $type['name']], 'status' => true]
            );
        }
    }

    protected function seedSkills(): void
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Python', 'Django',
            'Node.js', 'SQL', 'MySQL', 'PostgreSQL', 'Docker', 'AWS', 'Git',
            'Project Management', 'Communication', 'Figma', 'Adobe Photoshop',
            'SEO', 'Content Writing', 'Data Analysis', 'Excel', 'Customer Support',
            'Sales', 'Digital Marketing', 'HTML/CSS', 'TypeScript', 'REST APIs',
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['slug' => Str::slug($skill)], ['name' => $skill]);
        }
    }
}
