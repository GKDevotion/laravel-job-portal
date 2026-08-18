<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $guard_name = 'web'; // job seekers use the default "web" guard

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
