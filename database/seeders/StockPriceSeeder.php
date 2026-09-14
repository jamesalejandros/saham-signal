<?php

namespace Database\Seeders;

use App\Models\StockPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StockPriceSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = ['BBCA', 'BBRI', 'TLKM', 'ASII', 'UNVR', 'GOTO', 'BMRI', 'ANTM'];

        foreach ($stocks as $code) {
            $this->seedStock($code);
        }
    }

    private function seedStock(string $stockCode, int $days = 200): void
    {
        mt_srand(crc32($stockCode));

        $price = mt_rand(1000, 10000) / 10;
        $baseVolume = mt_rand(100000, 5000000);
        $date = Carbon::today()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $date->copy()->addDay();

            // skip weekends, since IDX doesn't trade then
            if ($date->isWeekend()) {
                continue;
            }

            $changePercent = mt_rand(-300, 320) / 10000;
            $price = max($price * (1 + $changePercent), 1);

            $volume = $baseVolume * (mt_rand(70, 130) / 100);
            if (mt_rand(1, 100) <= 15) {
                $volume *= mt_rand(150, 300) / 100;
            }

            StockPrice::updateOrCreate(
                ['stock_code' => $stockCode, 'date' => $date->toDateString()],
                ['close_price' => round($price, 2), 'volume' => (int) $volume]
            );
        }

        mt_srand();
    }
}