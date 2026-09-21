<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        /*
        |--------------------------------------------------------------------------
        | Default Stocks
        |--------------------------------------------------------------------------
        |
        | Setiap user baru otomatis mendapatkan 10 saham default.
        | Tidak random, sehingga semua user baru memiliki watchlist
        | awal yang sama.
        |
        */

        $defaultStockCodes = [
            'BBCA',
            'BBRI',
            'BMRI',
            'BBNI',
            'TLKM',
            'ASII',
            'ICBP',
            'INDF',
            'ANTM',
            'PTBA',
        ];

        /*
        |--------------------------------------------------------------------------
        | Pastikan hanya stock yang memang tersedia di database
        |--------------------------------------------------------------------------
        */

        $stocks = Stock::query()
            ->whereIn('stock_code', $defaultStockCodes)
            ->pluck('stock_code');

        /*
        |--------------------------------------------------------------------------
        | Masukkan ke user_stocks
        |--------------------------------------------------------------------------
        */

        if ($stocks->isNotEmpty()) {
            $user->stocks()->sync($stocks);
        }
    }
}
