<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPrice extends Model
{
    protected $fillable = ['stock_code', 'date', 'close_price', 'volume'];

    protected $casts = [
        'date' => 'date',
        'close_price' => 'float',
    ];
}