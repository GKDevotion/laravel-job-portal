<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    public const STATUSES = ['submitted', 'reviewing', 'shortlisted', 'interview', 'rejected', 'hired', 'withdrawn'];

    protected $fillable = ['job_id', 'candidate_id', 'status', 'cover_letter', 'resume_path', 'employer_notes', 'reviewed_at'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function candidate(): BelongsTo { return $this->belongsTo(User::class, 'candidate_id'); }
}
