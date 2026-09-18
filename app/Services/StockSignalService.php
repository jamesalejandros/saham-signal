<?php

namespace App\Services;

use App\Models\StockSignal;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\StockSignalNotification;
use App\Notifications\StockSignalTelegramNotification;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Notification;
use Throwable;

class StockSignalService
{
    public function __construct(
        private StockPriceProvider $provider,
    ) {}

    public function generateSignal(string $stockCode): StockSignal
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil data harga dan volume
        |--------------------------------------------------------------------------
        |
        | 200 harga penutupan digunakan agar indikator seperti MA50
        | mempunyai data historis yang cukup.
        |
        | 21 volume digunakan karena perhitungan volume membutuhkan
        | volume saat ini + 20 periode sebelumnya.
        |
        */

        $prices = $this->provider->getClosingPrices($stockCode, 200);
        $volumes = $this->provider->getVolumes($stockCode, 21);

        /*
        |--------------------------------------------------------------------------
        | 2. Jalankan seluruh indikator
        |--------------------------------------------------------------------------
        |
        | Calculator menentukan:
        |
        | condition_1 = kondisi Moving Average
        | condition_2 = kondisi RSI
        | condition_3 = kondisi Volume
        |
        | direction = bullish / bearish
        | strength  = jumlah kondisi yang terpenuhi
        |
        */

        $result = StockSignalCalculator::resolveDirection(
            $prices,
            $volumes
        );

        $isBullish = $result['direction'] === 'bullish';
        $isBearish = $result['direction'] === 'bearish';
        $strength = $result['strength'];

        /*
        |--------------------------------------------------------------------------
        | 3. Tentukan signal
        |--------------------------------------------------------------------------
        |
        | Jumlah kondisi yang terpenuhi menentukan kekuatan signal.
        |
        | 3 kondisi = STRONG
        | 2 kondisi = NORMAL
        | 1 kondisi = WEAK
        | 0 kondisi = HOLD
        |
        */

        [$signal, $signalStrength] = match (true) {

            $strength === 3 && $isBullish =>
                ['BUY', 'STRONG'],

            $strength === 2 && $isBullish =>
                ['BUY', 'NORMAL'],

            $strength === 1 && $isBullish =>
                ['BUY', 'WEAK'],

            $strength === 3 && $isBearish =>
                ['SELL', 'STRONG'],

            $strength === 2 && $isBearish =>
                ['SELL', 'NORMAL'],

            $strength === 1 && $isBearish =>
                ['SELL', 'WEAK'],

            default =>
                ['HOLD', 'NONE'],
        };

        /*
        |--------------------------------------------------------------------------
        | 4. Buat penjelasan signal
        |--------------------------------------------------------------------------
        |
        | Penjelasan tidak hanya mengatakan berapa kondisi yang terpenuhi,
        | tetapi menjelaskan alasan di balik masing-masing kondisi.
        |
        */

        $description = $this->buildDescription(
            $result,
            $signal,
            $signalStrength,
            $strength
        );

        /*
        |--------------------------------------------------------------------------
        | 5. Simpan hasil signal
        |--------------------------------------------------------------------------
        */

        $signalRecord = StockSignal::create([
            'stock_code' => $stockCode,

            'condition_1' => $result['condition_1'],
            'condition_2' => $result['condition_2'],
            'condition_3' => $result['condition_3'],

            'signal' => $signal,
            'signal_strength' => $signalStrength,
            'description' => $description,
        ]);

        Log::info("Signal generated for {$stockCode}", [
            'signal' => $signal,
            'strength' => $signalStrength,
            'conditions_met' => $strength,
            'description' => $description,
        ]);

        $stock = Stock::where("stock_code", $stockCode)->first();
        /*
        |--------------------------------------------------------------------------
        | 6. Kirim notifikasi
        |--------------------------------------------------------------------------
        |
        | HOLD tidak mengirim notifikasi karena tidak ada keputusan
        | BUY atau SELL yang perlu diberitahukan.
        |
        */

       if ($signal !== 'HOLD' && $signalStrength !== 'WEAK') {
            $this->notify($signalRecord);
        }

        return $signalRecord;
    }

    /**
     * Membuat penjelasan yang lebih lengkap berdasarkan
     * kondisi indikator yang dihasilkan calculator.
     */
    private function buildDescription(
        array $result,
        string $signal,
        string $signalStrength,
        int $strength
    ): string {
        if ($signal === 'HOLD') {
            return implode("\n", [
                '- HOLD: belum ada konfirmasi BUY atau SELL yang cukup kuat.',
                '- MA, RSI, dan volume belum mendukung arah yang jelas.',
                '- Tunggu konfirmasi berikutnya.',
            ]);
        }

        $points = [
            "- {$signal} {$signalStrength}: {$strength}/3 kondisi terpenuhi.",
            $result['condition_1']
                ? ($signal === 'BUY' ? '- MA20 > MA50: tren bullish terkonfirmasi.' : '- MA20 < MA50: tren bearish terkonfirmasi.')
                : '- MA20 dan MA50: belum mengonfirmasi tren.',
            $result['condition_2']
                ? ($signal === 'BUY' ? '- RSI oversold: ada peluang rebound.' : '- RSI overbought: ada risiko koreksi.')
                : '- RSI: belum memberi konfirmasi tambahan.',
            $result['condition_3']
                ? '- Volume: meningkat dan mendukung pergerakan harga.'
                : '- Volume: belum meningkat signifikan.',
            "- Kesimpulan: sinyal {$signal} dengan kekuatan {$signalStrength}.",
        ];

        return implode("\n", $points);
    }

    /**
     * Mengirim notifikasi kepada seluruh user dan Telegram.
     */
    private function notify(StockSignal $signalRecord): void
    {
        try {

            Log::info(
                "Sending notifications for {$signalRecord->stock_code} ({$signalRecord->signal})..."
            );

            /*
            |--------------------------------------------------------------------------
            | Notifikasi aplikasi
            |--------------------------------------------------------------------------
            |
            | Semua user menerima signal untuk semua saham.
            | Tidak menggunakan watchlist karena sistem memang
            | tidak membatasi saham berdasarkan preferensi user.
            |
            */

            $users = User::role('user')->get();
            Log::info($users);
            foreach ($users as $user) {
                $user->notify(
                    new StockSignalNotification($signalRecord)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Notifikasi Telegram
            |--------------------------------------------------------------------------
            */

            Notification::route(
                'telegram',
                config('services.telegram-bot-api.chat_id')
            )->notify(
                new StockSignalTelegramNotification($signalRecord)
            );

            Log::info(
                "Notifications sent for {$signalRecord->stock_code}"
            );

        } catch (Throwable $err) {

            Log::error(
                "Failed to send notifications for {$signalRecord->stock_code}",
                [
                    'exception' => $err->getMessage(),
                    'trace' => $err->getTraceAsString(),
                ]
            );
        }
    }
}