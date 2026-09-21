<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockSignal extends Model
{
    protected $fillable = [
        'stock_code',
        'condition_1',
        'condition_2',
        'condition_3',
        'signal',
        'signal_strength',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'condition_1' => 'boolean',
            'condition_2' => 'boolean',
            'condition_3' => 'boolean',
        ];
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(
            Stock::class,
            'stock_code',
            'stock_code'
        );
    }
}
