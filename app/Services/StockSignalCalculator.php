<?php

namespace App\Services;

use App\Models\StockSignal;

class StockSignalCalculator
{
    public static function movingAverage(array $prices, int $period): ?float
    {
        if (count($prices) < $period) {
            return null; // not enough data
        }

        $slice = array_slice($prices, -$period);

        return array_sum($slice) / $period;
    }

    public static function isMaCrossoverBullish(array $prices, int $shortPeriod = 20, int $longPeriod = 50): bool
    {
        $shortMa = self::movingAverage($prices, $shortPeriod);
        $longMa  = self::movingAverage($prices, $longPeriod);

        if ($shortMa === null || $longMa === null) {
            return false;
        }

        return $shortMa > $longMa;
    }

    // NEW — bearish counterpart, needed for sell signals
    public static function isMaCrossoverBearish(array $prices, int $shortPeriod = 20, int $longPeriod = 50): bool
    {
        $shortMa = self::movingAverage($prices, $shortPeriod);
        $longMa  = self::movingAverage($prices, $longPeriod);

        if ($shortMa === null || $longMa === null) {
            return false;
        }

        return $shortMa < $longMa;
    }

    public static function rsi(array $prices, int $period = 14): ?float
    {
        if (count($prices) < $period + 1) {
            return null;
        }

        $slice = array_slice($prices, -($period + 1));

        $gains = 0.0;
        $losses = 0.0;

        for ($i = 1; $i < count($slice); $i++) {
            $change = $slice[$i] - $slice[$i - 1];

            if ($change > 0) {
                $gains += $change;
            } else {
                $losses += abs($change);
            }
        }

        $avgGain = $gains / $period;
        $avgLoss = $losses / $period;

        if ($avgLoss == 0) {
            return 100.0;
        }

        $rs = $avgGain / $avgLoss;

        return 100 - (100 / (1 + $rs));
    }

    public static function isOversold(array $prices, int $period = 14, float $threshold = 30): bool
    {
        $rsi = self::rsi($prices, $period);

        return $rsi !== null && $rsi < $threshold;
    }

    public static function isOverbought(array $prices, int $period = 14, float $threshold = 70): bool
    {
        $rsi = self::rsi($prices, $period);

        return $rsi !== null && $rsi > $threshold;
    }

    public static function isVolumeConfirmed(array $volumes, int $period = 20, float $multiplier = 1.5): bool
    {
        if (count($volumes) < $period + 1) {
            return false;
        }

        $currentVolume = end($volumes);
        $historical = array_slice($volumes, -($period + 1), $period);

        $avgVolume = array_sum($historical) / $period;

        return $currentVolume > ($avgVolume * $multiplier);
    }

    // StockSignalCalculator.php — replace determineSignal() with this:
    public static function resolveDirection(array $prices, array $volumes): array
    {
        $bullish = [
            self::isMaCrossoverBullish($prices),
            self::isOversold($prices),
            self::isVolumeConfirmed($volumes),
        ];

        $bearish = [
            self::isMaCrossoverBearish($prices),
            self::isOverbought($prices),
            self::isVolumeConfirmed($volumes),
        ];

        $buyStrength  = count(array_filter($bullish));
        $sellStrength = count(array_filter($bearish));

        if ($buyStrength >= $sellStrength) {
            return [
                'direction' => 'bullish',
                'strength' => $buyStrength,
                'condition_1' => $bullish[0],
                'condition_2' => $bullish[1],
                'condition_3' => $bullish[2],
            ];
        }

        return [
            'direction' => 'bearish',
            'strength' => $sellStrength,
            'condition_1' => $bearish[0],
            'condition_2' => $bearish[1],
            'condition_3' => $bearish[2],
        ];
    }
}