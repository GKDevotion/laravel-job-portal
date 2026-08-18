<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name', 'code', 'symbol', 'exchange_rate', 'symbol_position', 'is_default', 'status',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:6',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ];
    }

    // Format an amount (stored in the base currency) into this currency's display format
    public function format(float $amountInBaseCurrency): string
    {
        $converted = $amountInBaseCurrency * $this->exchange_rate;
        $formatted = number_format($converted, 2);

        return $this->symbol_position === 'before'
            ? "{$this->symbol}{$formatted}"
            : "{$formatted}{$this->symbol}";
    }
}
