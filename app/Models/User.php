<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    public const ROLE_SUPER_ADMIN='super_admin';
    public const ROLE_ADMIN='admin';
    public const ROLE_EMPLOYER='employer';
    public const ROLE_CANDIDATE='candidate';
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
    public function candidateProfile(): HasOne { return $this->hasOne(CandidateProfile::class); }
    public function employerProfile(): HasOne { return $this->hasOne(EmployerProfile::class); }
    public function applications(): HasMany { return $this->hasMany(JobApplication::class, 'candidate_id'); }
    public function savedJobs() { return $this->belongsToMany(Job::class, 'saved_jobs')->withTimestamps(); }
    public function isAdmin(): bool { return in_array($this->role,[self::ROLE_SUPER_ADMIN,self::ROLE_ADMIN],true); }
}
