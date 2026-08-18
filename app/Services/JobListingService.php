<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Employee;
use App\Models\JobListing;
use Illuminate\Support\Str;

class JobListingService
{
    public function create(array $data, Company $company, ?Employee $postedBy = null): JobListing
    {
        $data['company_id'] = $company->id;
        $data['posted_by'] = $postedBy?->id;
        $data['slug'] = $this->uniqueSlug($data['title']['en'] ?? 'job');

        // New jobs always start as draft or pending_review — never auto-published.
        // The company decides to submit-for-review from the edit screen (see publish()).
        $data['status'] = 'draft';

        return JobListing::create($data);
    }

    public function update(JobListing $jobListing, array $data): JobListing
    {
        // Editing a live job sends it back for admin review rather than silently changing
        // a published listing — keeps the public board trustworthy.
        if ($jobListing->status === 'published') {
            $data['status'] = 'pending_review';
        }

        $jobListing->update($data);

        if (isset($data['skills'])) {
            $jobListing->skills()->sync($data['skills']);
        }

        return $jobListing;
    }

    public function submitForReview(JobListing $jobListing): JobListing
    {
        $jobListing->update(['status' => 'pending_review']);

        return $jobListing;
    }

    public function close(JobListing $jobListing): JobListing
    {
        $jobListing->update(['status' => 'closed']);

        return $jobListing;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (JobListing::where('slug', $slug)->exists()) {
            $slug = "{$base}-".Str::random(4);
            $i++;
            if ($i > 20) {
                break; // safety valve against pathological loops
            }
        }

        return $slug;
    }
}
