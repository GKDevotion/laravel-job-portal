<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobListing::published()->with(['company', 'category', 'type', 'currency']);

        // --- Search ---
        if ($keyword = $request->string('q')->trim()->toString()) {
            $locale = app()->getLocale();
            $query->where(function ($q) use ($keyword, $locale) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"{$locale}\"')) LIKE ?", ["%{$keyword}%"])
                  ->orWhereHas('company', fn ($c) => $c->where('company_name', 'like', "%{$keyword}%"));
            });
        }

        // --- Filters ---
        if ($request->filled('category')) {
            $query->where('job_category_id', $request->integer('category'));
        }

        if ($request->filled('type')) {
            $query->where('job_type_id', $request->integer('type'));
        }

        if ($request->filled('work_mode')) {
            $query->where('work_mode', $request->string('work_mode'));
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->string('experience_level'));
        }

        if ($request->filled('country')) {
            $query->where('country', $request->string('country'));
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->integer('salary_min'));
        }

        // Featured jobs first, always — this must be the FIRST orderBy call. Eloquent stacks
        // multiple orderBy() calls in the order they're chained, so adding this after the
        // sort-mode match() below would make it a tie-breaker instead of the primary sort.
        $query->orderByDesc('is_featured');

        // --- Sort ---
        match ($request->string('sort')->toString()) {
            'salary_high' => $query->orderByDesc('salary_max'),
            'salary_low' => $query->orderBy('salary_min'),
            default => $query->latest('published_at'),
        };

        $jobs = $query->paginate(15)->withQueryString();

        return view('front.jobs.index', [
            'jobs' => $jobs,
            'categories' => JobCategory::where('status', true)->orderBy('order')->get(),
            'types' => JobType::where('status', true)->get(),
            'filters' => $request->only(['q', 'category', 'type', 'work_mode', 'experience_level', 'country', 'sort']),
        ]);
    }

    public function show(JobListing $jobListing): View
    {
        abort_unless($jobListing->isOpen() || (auth('company')->check() && auth('company')->id() === $jobListing->company_id), 404);

        $jobListing->load(['company', 'category', 'type', 'currency', 'skills']);
        $jobListing->incrementViews();

        $alreadyApplied = auth('web')->check()
            && $jobListing->applications()->where('user_id', auth('web')->id())->exists();

        $relatedJobs = JobListing::published()
            ->where('job_category_id', $jobListing->job_category_id)
            ->where('id', '!=', $jobListing->id)
            ->limit(4)
            ->get();

        return view('front.jobs.show', compact('jobListing', 'alreadyApplied', 'relatedJobs'));
    }
}
