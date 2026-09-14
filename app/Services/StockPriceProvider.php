<?php

namespace App\Services;

class StockPriceProvider
{
    /**
     * Generate dummy closing prices using a random walk, oldest first.
     */
    public function getClosingPrices(string $stockCode, int $limit = 200): array
    {
        // Seed based on stock code so the same stock always gets the same "history"
        mt_srand(crc32($stockCode));

        $startPrice = mt_rand(1000, 10000) / 10; // e.g. 100.0 - 1000.0
        $prices = [$startPrice];

        for ($i = 1; $i < $limit; $i++) {
            $lastPrice = $prices[$i - 1];

            // Daily change: -3% to +3%, with a slight upward drift
            $changePercent = (mt_rand(-300, 320) / 10000);
            $newPrice = $lastPrice * (1 + $changePercent);

            // Floor so price never goes to zero/negative
            $prices[] = max($newPrice, 1);
        }

        // Reset random seed so it doesn't affect other parts of the app
        mt_srand();

        return $prices;
    }

    /**
     * Generate dummy daily volumes, oldest first, with occasional spikes.
     */
    public function getVolumes(string $stockCode, int $limit = 21): array
    {
        mt_srand(crc32($stockCode . '_volume'));

        $baseVolume = mt_rand(100000, 5000000);
        $volumes = [];

        for ($i = 0; $i < $limit; $i++) {
            // Normal daily fluctuation: 70% - 130% of base
            $volume = $baseVolume * (mt_rand(70, 130) / 100);

            // ~15% chance of a volume spike (1.5x - 3x), useful for testing condition_3
            if (mt_rand(1, 100) <= 15) {
                $volume *= mt_rand(150, 300) / 100;
            }

            $volumes[] = (int) $volume;
        }

        mt_srand();

        return $volumes;
    }
}