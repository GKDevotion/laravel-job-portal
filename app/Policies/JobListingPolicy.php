<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Employee;
use App\Models\JobListing;

class JobListingPolicy
{
    /**
     * $actor is either the authenticated Company or the authenticated Employee —
     * whichever guard is currently logged in when the controller calls Gate::authorize.
     */
    public function update(Company|Employee $actor, JobListing $jobListing): bool
    {
        return $this->belongsToActorsCompany($actor, $jobListing)
            && $this->actorCanManageJobs($actor);
    }

    public function delete(Company|Employee $actor, JobListing $jobListing): bool
    {
        return $this->update($actor, $jobListing);
    }

    public function view(Company|Employee $actor, JobListing $jobListing): bool
    {
        return $this->belongsToActorsCompany($actor, $jobListing);
    }

    protected function belongsToActorsCompany(Company|Employee $actor, JobListing $jobListing): bool
    {
        $companyId = $actor instanceof Company ? $actor->id : $actor->company_id;

        return $jobListing->company_id === $companyId;
    }

    protected function actorCanManageJobs(Company|Employee $actor): bool
    {
        if ($actor instanceof Company) {
            return true; // the employer account itself always has full rights over its own jobs
        }

        return $actor->can('post jobs') || $actor->can('edit jobs');
    }
}
