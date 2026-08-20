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

    /**
     * Maps this type's stable `slug` to schema.org's JobPosting.employmentType enum, used in
     * the front theme's structured data. Deliberately keyed off `slug` (not the translatable
     * `name`) since `name` is free-text an admin can edit/translate — parsing it would break
     * the moment someone renames "Full-time" to something schema.org doesn't recognize.
     * Falls back to OTHER for any slug not in this list (e.g. a custom type an admin added).
     */
    public function schemaEmploymentType(): string
    {
        return match ($this->slug) {
            'full-time' => 'FULL_TIME',
            'part-time' => 'PART_TIME',
            'contract' => 'CONTRACTOR',
            'internship' => 'INTERN',
            'temporary' => 'TEMPORARY',
            'volunteer' => 'VOLUNTEER',
            default => 'OTHER',
        };
    }
}
