<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'category_id',
        'title',
        'slug',
        'location',
        'job_type',
        'workplace_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'description',
        'requirements',
        'responsibilities',
        'is_featured',
        'status',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($builder) {
                $builder->whereNull('expires_at')->orWhereDate('expires_at', '>=', now()->toDateString());
            });
    }
}
