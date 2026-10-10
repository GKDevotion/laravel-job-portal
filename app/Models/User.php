<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $guard_name = 'web'; // job seekers use the default "web" guard

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();

        $this->addMediaCollection('resume')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 150, 150)
            ->nonQueued();
    }

    public function avatarUrl(): string
    {
        // Fall back to a generated initial-avatar if the seeker never uploaded a photo,
        // so templates never need a null check to render something reasonable.
        $url = $this->getFirstMediaUrl('avatar', 'thumb');

        return $url
            ? \App\Support\Media::absoluteUrl($url)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=6366f1&color=fff';
    }

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar', 'gender', 'date_of_birth',
        'country', 'state', 'city', 'address', 'designation', 'bio',
        'experience_level', 'expected_salary', 'resume',
        'linkedin', 'github', 'portfolio_url',
        'is_profile_public', 'is_open_to_work', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_profile_public' => 'boolean',
            'is_open_to_work' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedJobs(): BelongsToMany
    {
        return $this->belongsToMany(JobListing::class, 'saved_jobs')->withTimestamps();
    }

    public function followedCompanies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_followers')->withTimestamps();
    }
}
