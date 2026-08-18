<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class JobType extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'slug', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }
}
