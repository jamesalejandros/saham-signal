<?php

namespace App\Services;

use App\Models\StockPrice;

class StockPriceProvider
{
    public function getClosingPrices(string $stockCode, int $limit = 200): array
    {
        return StockPrice::where('stock_code', $stockCode)
            ->orderBy('date') // oldest first — calculator assumes this order
            ->limit($limit)
            ->pluck('close_price')
            ->map(fn ($p) => (float) $p)
            ->toArray();
    }

    public function getVolumes(string $stockCode, int $limit = 21): array
    {
        return StockPrice::where('stock_code', $stockCode)
            ->orderBy('date')
            ->limit($limit)
            ->pluck('volume')
            ->map(fn ($v) => (int) $v)
            ->toArray();
    }
}