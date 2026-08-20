<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class JobListing extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'job_listings';

    public array $translatable = [
        'title', 'description', 'responsibilities', 'requirements', 'benefits',
        'meta_title', 'meta_description',
    ];

    protected $fillable = [
        'company_id', 'posted_by', 'job_category_id', 'job_type_id', 'currency_id',
        'title', 'slug', 'description', 'responsibilities', 'requirements', 'benefits',
        'salary_min', 'salary_max', 'salary_period', 'is_salary_negotiable',
        'work_mode', 'country', 'state', 'city', 'latitude', 'longitude',
        'experience_level', 'vacancies', 'application_deadline',
        'status', 'rejection_reason', 'is_featured', 'published_at',
        'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'is_salary_negotiable' => 'boolean',
            'is_featured' => 'boolean',
            'application_deadline' => 'date',
            'published_at' => 'datetime',
        ];
    }

    // --- Relationships ---

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'posted_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_listing_skill');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

    // --- Query scopes ---

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now()->toDateString());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    // --- Helpers ---

    public function isOpen(): bool
    {
        return $this->status === 'published'
            && (! $this->application_deadline || $this->application_deadline->isFuture());
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Makes route('jobs.show', $job) generate /jobs/{slug} instead of /jobs/{id}. The
     * {jobListing:slug} suffix used in routes/web.php only governs INBOUND resolution (which
     * column an incoming URL is matched against) — outbound URL generation via the model
     * separately calls getRouteKey(), which defaults to the primary key unless overridden here.
     * Without this, route() calls would silently generate ID-based URLs that don't match the
     * slug-based route pattern.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
