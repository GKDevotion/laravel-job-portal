<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class Company extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $guard_name = 'company';

    // Logo and cover image: single-file collections (uploading a new one replaces the old,
    // and old files are deleted automatically — no orphaned files left on disk/S3).
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('cover_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 200, 200)
            ->nonQueued(); // keep synchronous since queue_conversions_by_default is off

        $this->addMediaConversion('cover')
            ->fit(Fit::Crop, 1200, 400)
            ->nonQueued();
    }

    public function logoUrl(): ?string
    {
        $url = $this->getFirstMediaUrl('logo', 'thumb');

        return $url ? \App\Support\Media::absoluteUrl($url) : null;
    }

    public function coverImageUrl(): ?string
    {
        $url = $this->getFirstMediaUrl('cover_image', 'cover');

        return $url ? \App\Support\Media::absoluteUrl($url) : null;
    }

    protected $fillable = [
        'company_name', 'slug', 'email', 'password', 'phone',
        'logo', 'cover_image', 'about', 'website', 'industry', 'team_size',
        'founded_year', 'country', 'state', 'city', 'address', 'latitude', 'longitude',
        'facebook', 'twitter', 'linkedin', 'instagram',
        'is_verified', 'approval_status', 'rejection_reason', 'status', 'currency_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'status' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // --- Job posting credits ---
    // null = untracked/unlimited (default for companies that predate the credits system
    // or haven't purchased a pack yet). Once set to a number, it's enforced.

    public function hasUnlimitedCredits(): bool
    {
        return is_null($this->job_posting_credits);
    }

    public function hasCreditsAvailable(): bool
    {
        return $this->hasUnlimitedCredits() || $this->job_posting_credits > 0;
    }

    public function consumeCredit(): void
    {
        if (! $this->hasUnlimitedCredits()) {
            $this->decrement('job_posting_credits');
        }
    }

    public function addCredits(int $amount): void
    {
        // First purchase moves a company from "unlimited/untracked" to a real tracked balance.
        $this->update(['job_posting_credits' => ($this->job_posting_credits ?? 0) + $amount]);
    }

    // Only approved + active companies may post jobs / log in productively
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved' && $this->status;
    }

    /**
     * See JobListing::getRouteKeyName() for why this is required — without it, route()
     * would generate ID-based URLs that don't match the {company:slug} route pattern.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
