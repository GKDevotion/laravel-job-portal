<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $baseCurrency = Currency::where('is_default', true)->first();

        $plans = [
            [
                'name' => ['en' => '5 Job Posting Credits'],
                'description' => ['en' => 'Post up to 5 jobs. Credits never expire.'],
                'type' => 'job_credits',
                'price' => 99,
                'credits' => 5,
                'order' => 1,
            ],
            [
                'name' => ['en' => '15 Job Posting Credits'],
                'description' => ['en' => 'Best value for growing teams hiring regularly.'],
                'type' => 'job_credits',
                'price' => 249,
                'credits' => 15,
                'order' => 2,
            ],
            [
                'name' => ['en' => 'Featured Listing Boost'],
                'description' => ['en' => 'Pin one job to the top of search results and the homepage.'],
                'type' => 'featured_job',
                'price' => 49,
                'featured_days' => 14,
                'order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::firstOrCreate(
                ['name->en' => $plan['name']['en']],
                array_merge($plan, ['currency_id' => $baseCurrency?->id, 'status' => true])
            );
        }
    }
}
