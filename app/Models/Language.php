<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name', 'code', 'locale', 'flag', 'is_rtl', 'is_default', 'status', 'order',
    ];

    protected function casts(): array
    {
        return [
            'is_rtl' => 'boolean',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ];
    }
}
