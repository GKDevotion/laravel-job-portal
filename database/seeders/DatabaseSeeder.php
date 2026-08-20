<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesPermissionsSeeder::class, // roles/permissions + languages + currencies
            AdminSeeder::class,            // default super admin login
            JobTaxonomySeeder::class,      // categories, job types, skills — required before any job can be posted
            PricingPlanSeeder::class,      // sample job-credit and featured-boost plans
            DemoDataSeeder::class,         // Demo Data Imported
        ]);
    }
}
