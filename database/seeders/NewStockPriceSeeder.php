<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NewStockPriceSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = Stock::query()
            ->whereNotIn('stock_code', [
                'BBCA',
                'BBRI',
                'TLKM',
                'ASII',
                'UNVR',
                'GOTO',
                'BMRI',
                'ANTM',
            ])
            ->pluck('stock_code');

        foreach ($stocks as $code) {
            $this->seedStock($code);
        }
    }

    private function seedStock(string $stockCode, int $days = 200): void
    {
        // Agar setiap saham mendapatkan pola harga yang konsisten
        // setiap kali seeder dijalankan.
        mt_srand(crc32($stockCode));

        $price = mt_rand(500, 100000) / 10;
        $baseVolume = mt_rand(100000, 5000000);

        $date = Carbon::today()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $date->copy()->addDay();

            // Bursa tidak melakukan perdagangan pada weekend.
            if ($date->isWeekend()) {
                continue;
            }

            // Pergerakan harga sekitar -3% sampai +3.2%.
            $changePercent = mt_rand(-300, 320) / 10000;

            $price = max(
                $price * (1 + $changePercent),
                50
            );

            $volume = $baseVolume * (mt_rand(70, 130) / 100);

            // Sesekali buat volume transaksi lebih tinggi.
            if (mt_rand(1, 100) <= 15) {
                $volume *= mt_rand(150, 300) / 100;
            }

            StockPrice::updateOrCreate(
                [
                    'stock_code' => $stockCode,
                    'date' => $date->toDateString(),
                ],
                [
                    'close_price' => round($price, 2),
                    'volume' => (int) $volume,
                ]
            );
        }

        mt_srand();
    }
}
