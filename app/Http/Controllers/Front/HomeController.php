<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('front.home', [
            'featuredJobs' => JobListing::published()->featured()->with(['company', 'type'])->latest('published_at')->limit(6)->get(),
            'latestJobs' => JobListing::published()->with(['company', 'type'])->latest('published_at')->limit(8)->get(),
            'categories' => JobCategory::where('status', true)->withCount([
                'jobListings' => fn ($q) => $q->published(),
            ])->orderBy('order')->limit(8)->get(),
            'topCompanies' => Company::where('approval_status', 'approved')->where('status', true)
                ->withCount(['jobListings' => fn ($q) => $q->published()])
                ->orderByDesc('job_listings_count')
                ->limit(8)->get(),
        ]);
    }
}
