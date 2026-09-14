<?php

namespace App\Services;

use App\Models\StockSignal;
use App\Models\User;
use App\Notifications\StockSignalNotification;
use App\Notifications\StockSignalTelegramNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class StockSignalService
{
    public function __construct(
        private StockPriceProvider $provider,
    ) {}

    public function generateSignal(string $stockCode, string $stockName): StockSignal
    {
        $prices  = $this->provider->getClosingPrices($stockCode, 200);
        $volumes = $this->provider->getVolumes($stockCode, 21);

        $result = StockSignalCalculator::resolveDirection($prices, $volumes);

        $isBullish = $result['direction'] === 'bullish';
        $strength  = $result['strength'];

        [$signal, $signalStrength, $description] = match (true) {
            $strength === 3 && $isBullish => ['BUY', 'STRONG', '3 dari 3 kondisi terpenuhi. Sinyal beli kuat.'],
            $strength === 2 && $isBullish => ['BUY', 'NORMAL', '2 dari 3 kondisi terpenuhi. Sinyal beli normal.'],
            $strength === 1 && $isBullish => ['BUY', 'WEAK', '1 dari 3 kondisi terpenuhi. Sinyal beli lemah.'],
            $strength === 3 && !$isBullish => ['SELL', 'STRONG', '3 dari 3 kondisi terpenuhi. Sinyal jual kuat.'],
            $strength === 2 && !$isBullish => ['SELL', 'NORMAL', '2 dari 3 kondisi terpenuhi. Sinyal jual normal.'],
            $strength === 1 && !$isBullish => ['SELL', 'WEAK', '1 dari 3 kondisi terpenuhi. Sinyal jual lemah.'],
            default => ['HOLD', 'NONE', 'Tidak ada kondisi beli/jual yang terpenuhi.'],
        };

        $signalRecord = StockSignal::create([
            'stock_code'      => $stockCode,
            'stock_name'      => $stockName,
            'condition_1'     => $result['condition_1'],
            'condition_2'     => $result['condition_2'],
            'condition_3'     => $result['condition_3'],
            'signal'          => $signal,
            'signal_strength' => $signalStrength,
            'description'     => $description,
        ]);

        Log::info("Signal generated for {$stockCode}", $signalRecord->toArray());

        if ($signal !== 'HOLD') {
            $this->notify($signalRecord);
        }

        return $signalRecord;
    }

    private function notify(StockSignal $signalRecord): void
    {
        try {
            Log::info("Sending notifications for {$signalRecord->stock_code} ({$signalRecord->signal})...");

            $users = User::role('user')->get();

            foreach ($users as $user) {
                $user->notify(new StockSignalNotification($signalRecord));
            }

            Notification::route(
                'telegram',
                config('services.telegram-bot-api.chat_id')
            )->notify(new StockSignalTelegramNotification($signalRecord));

            Log::info("Notifications sent for {$signalRecord->stock_code}");
        } catch (\Throwable $err) {
            Log::error("Failed to send notifications for {$signalRecord->stock_code}", [
                'exception' => $err->getMessage(),
                'trace'     => $err->getTraceAsString(),
            ]);
        }
    }
}