<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $table = 'job_listings';
    protected $fillable = ['company_id','category_id','title','slug','location','job_type','workplace_type','experience_level','salary_min','salary_max','salary_currency','description','requirements','responsibilities','is_featured','status','published_at','expires_at'];
    protected function casts(): array { return ['is_featured'=>'boolean','published_at'=>'datetime','expires_at'=>'date','salary_min'=>'decimal:2','salary_max'=>'decimal:2']; }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function category(): BelongsTo { return $this->belongsTo(JobCategory::class, 'category_id'); }
    public function skills() { return $this->belongsToMany(Skill::class, 'job_skill'); }
    public function applications(): HasMany { return $this->hasMany(JobApplication::class); }
    public function savedBy() { return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps(); }
    public function scopePublished($query) { return $query->where('status','published')->where(fn($builder) => $builder->whereNull('expires_at')->orWhereDate('expires_at','>=',now()->toDateString())); }
}
