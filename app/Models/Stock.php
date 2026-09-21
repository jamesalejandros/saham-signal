<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->hasMany(
            StockPrice::class,
            'stock_code',
            'stock_code'
        );
    }

    public function signals(): HasMany
    {
        return $this->hasMany(
            StockSignal::class,
            'stock_code',
            'stock_code'
        );
    }

    /**
     * User yang memilih saham ini.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_stocks',
            'stock_code',
            'user_id',
            'stock_code',
            'id'
        )->withTimestamps();
    }
}
