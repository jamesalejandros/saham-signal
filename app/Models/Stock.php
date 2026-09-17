<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $primaryKey = 'stock_code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'stock_code',
        'stock_name',
        'summary',
        'summary_updated',
    ];

    protected $casts = [
        'summary_updated' => 'datetime',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(StockPrice::class, 'stock_code', 'stock_code');
    }

    public function signals(): HasMany
    {
        return $this->hasMany(StockSignal::class, 'stock_code', 'stock_code');
    }
}