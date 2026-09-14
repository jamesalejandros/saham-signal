<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSignal extends Model
{
    protected $fillable = [
        'stock_code',
        'stock_name',
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
}
