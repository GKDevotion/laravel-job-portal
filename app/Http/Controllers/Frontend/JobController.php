<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $jobs = Job::query()->published()->with(['company', 'category'])
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim($request->string('keyword')->toString());
                $query->where(function ($search) use ($keyword) {
                    $search->where('title', 'like', "%{$keyword}%")
                        ->orWhere('location', 'like', "%{$keyword}%")
                        ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->when($request->filled('location'), fn ($query) => $query->where('location', 'like', '%'.trim($request->input('location')).'%'))
            ->when($request->filled('job_type'), fn ($query) => $query->where('job_type', $request->input('job_type')))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->input('category'))))
            ->latest('published_at')->paginate(10)->withQueryString();

        return view('frontend.jobs.index', compact('jobs'));
    }

    public function show(string $slug): View
    {
        $job = Job::query()->published()->with(['company', 'category'])->where('slug', $slug)->firstOrFail();
        $relatedJobs = Job::query()->published()->with(['company', 'category'])
            ->where('id', '!=', $job->id)->where('category_id', $job->category_id)
            ->latest('published_at')->limit(3)->get();

        return view('frontend.jobs.show', compact('job', 'relatedJobs'));
    }
}
