<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StockPriceProvider
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.sectors.app/v2';
    public function __construct()
    {
        $this->apiKey = config('services.sectors.key');
    }

    public function getClosingPrices(string $stockCode, int $limit = 200): array
    {
        return StockPrice::where('stock_code', $stockCode)
            ->orderByDesc('date')   // newest first — to select the most recent $limit rows
            ->limit($limit)
            ->get()
            ->sortBy('date')        // then flip to oldest-first — calculator assumes this order
            ->pluck('close_price')
            ->map(fn ($p) => (float) $p)
            ->values()
            ->toArray();
    }

    public function getVolumes(string $stockCode, int $limit = 21): array
    {
        return StockPrice::where('stock_code', $stockCode)
            ->orderByDesc('date')
            ->limit($limit)
            ->get()
            ->sortBy('date')
            ->pluck('volume')
            ->map(fn ($v) => (int) $v)
            ->values()
            ->toArray();
    }
     /**
     * Fetch the latest 50 daily prices for every Stock from the Sectors API
     * and upsert them into stock_prices (no duplicates on rerun).
     */
    public function importLatestPricesForAllStocks(int $days = 50): void
    {
        $stocks = Stock::pluck('stock_code');

        foreach ($stocks as $stockCode) {
            $this->importLatestPrices($stockCode, $days);
        }
    }

    /**
     * Fetch and upsert the latest $days of prices for a single stock code.
     */
   public function importLatestPrices(string $stockCode, int $targetDays = 50): void
    {
        $existingCount = StockPrice::where('stock_code', $stockCode)->count();
        $latestDate = StockPrice::where('stock_code', $stockCode)->max('date');
        $today = date('Y-m-d');

        // Already have today's (or later) data — nothing new to fetch, skip entirely
        if ($latestDate && $latestDate >= $today) {
            Log::info("Skipping {$stockCode} — already up to date as of {$latestDate}");
            return;
        }

        if ($existingCount >= $targetDays && $latestDate) {
            // Already have enough rows — just catch up from the day after our latest date
            $start = date('Y-m-d', strtotime($latestDate . ' +1 day'));
            $end = $today;
        } else {
            // No data, or not enough trading days yet — pull a wide window and
            // let the trim step below cut it down to the newest $targetDays.
            $end = $today;
            $start = date('Y-m-d', strtotime('-90 days'));
        }

        Log::info("Contacting Sectors API for {$stockCode} from {$start} to {$end}...");

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->get("{$this->baseUrl}/daily/{$stockCode}/", [
            'start' => $start,
            'end' => $end,
        ]);

        if ($response->failed()) {
            Log::error('Sectors API request failed', [
                'stock' => $stockCode,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return;
        }

        $rows = $response->json();

        foreach ($rows as $row) {
            $cleanCode = strtoupper(explode('.', $row['symbol'])[0]);

            StockPrice::updateOrCreate(
                [
                    'stock_code' => $cleanCode,
                    'date' => $row['date'],
                ],
                [
                    'close_price' => $row['close'],
                    'volume' => $row['volume'],
                ]
            );
        }

        Log::info("Imported prices for {$stockCode}", ['rows' => count($rows)]);

        $this->trimToLatest($stockCode, $targetDays);
    }
    /**
     * Keep only the newest $keep trading days for a stock — deletes anything older.
     */
    protected function trimToLatest(string $stockCode, int $keep = 50): void
    {
        $cutoffDate = StockPrice::where('stock_code', $stockCode)
            ->orderByDesc('date')
            ->skip($keep - 1)
            ->take(1)
            ->value('date');

        if ($cutoffDate) {
            $deleted = StockPrice::where('stock_code', $stockCode)
                ->where('date', '<', $cutoffDate)
                ->delete();

            if ($deleted > 0) {
                Log::info("Trimmed {$stockCode} to newest {$keep} rows", ['deleted' => $deleted]);
            }
        }
    }
}