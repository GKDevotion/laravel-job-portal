<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = JobCategory::query()
            ->where('is_active', true)
            ->withCount(['jobs' => fn ($query) => $query->published()])
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $featuredJobs = Job::query()->published()->where('is_featured', true)
            ->with(['company', 'category'])->latest('published_at')->limit(6)->get();

        $latestJobs = Job::query()->published()->with(['company', 'category'])
            ->latest('published_at')->limit(6)->get();

        $companyCount = Company::query()->where('is_active', true)->count();
        $jobCount = Job::query()->published()->count();

        return view('frontend.home', compact('categories', 'featuredJobs', 'latestJobs', 'companyCount', 'jobCount'));
    }
}
