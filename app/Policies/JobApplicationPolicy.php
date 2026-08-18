<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Employee;
use App\Models\JobApplication;

class JobApplicationPolicy
{
    public function view(Company|Employee $actor, JobApplication $application): bool
    {
        return $this->belongsToActorsCompany($actor, $application);
    }

    public function update(Company|Employee $actor, JobApplication $application): bool
    {
        if (! $this->belongsToActorsCompany($actor, $application)) {
            return false;
        }

        return $actor instanceof Company || $actor->can('review applications');
    }

    protected function belongsToActorsCompany(Company|Employee $actor, JobApplication $application): bool
    {
        $companyId = $actor instanceof Company ? $actor->id : $actor->company_id;

        return $application->jobListing->company_id === $companyId;
    }
}
