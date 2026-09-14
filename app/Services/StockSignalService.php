<?php

namespace App\Services;

use App\Models\StockSignal;

class StockSignalService
{
    public function generateSignal(
        string $stockCode,
        string $stockName,
        bool $condition1,
        bool $condition2,
        bool $condition3
    ): StockSignal {

        $totalCondition = collect([
            $condition1,
            $condition2,
            $condition3,
        ])->filter()->count();

        if ($totalCondition === 3) {

            $signal = 'BUY';

            $signalStrength = 'STRONG';

            $description =
                '3 dari 3 kondisi terpenuhi. ' .
                'Sinyal beli kuat.';

        } elseif ($totalCondition === 2) {

            $signal = 'BUY';

            $signalStrength = 'NORMAL';

            $description =
                '2 dari 3 kondisi terpenuhi. ' .
                'Sinyal beli normal.';

        } elseif ($totalCondition === 1) {

            $signal = 'BUY';

            $signalStrength = 'WEAK';

            $description =
                '1 dari 3 kondisi terpenuhi. ' .
                'Sinyal beli lemah.';

        } else {

            $signal = 'HOLD';

            $signalStrength = 'NONE';

            $description =
                'Tidak ada kondisi beli yang terpenuhi.';

        }

        return StockSignal::create([
            'stock_code' => $stockCode,
            'stock_name' => $stockName,

            'condition_1' => $condition1,
            'condition_2' => $condition2,
            'condition_3' => $condition3,

            'signal' => $signal,
            'signal_strength' => $signalStrength,

            'description' => $description,
        ]);
    }
}
