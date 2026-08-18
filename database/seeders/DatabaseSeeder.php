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
            PricingPlanSeeder::class,      // sample job-credit and featured-boost plans
        ]);
    }
}
