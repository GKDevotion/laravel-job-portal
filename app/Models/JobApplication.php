<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_listing_id', 'user_id', 'resume_path', 'cover_letter',
        'status', 'reviewed_by', 'internal_notes', 'status_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status_updated_at' => 'datetime',
        ];
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }

    // Central place for the status workflow so controllers stay thin
    public function moveTo(string $status, ?Employee $reviewer = null): void
    {
        $this->update([
            'status' => $status,
            'reviewed_by' => $reviewer?->id ?? $this->reviewed_by,
            'status_updated_at' => now(),
        ]);
    }
}
