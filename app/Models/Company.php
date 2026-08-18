<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Company extends Authenticatable implements HasMedia
{
    use Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $guard_name = 'company';

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

    // Only approved + active companies may post jobs / log in productively
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved' && $this->status;
    }
}
