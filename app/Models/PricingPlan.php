<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class PricingPlan extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'description', 'type', 'price', 'currency_id',
        'credits', 'featured_days', 'status', 'order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function isFeaturedJobPlan(): bool
    {
        return $this->type === 'featured_job';
    }

    public function isCreditsPlan(): bool
    {
        return $this->type === 'job_credits';
    }
}
