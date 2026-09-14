<?php

namespace App\Services;

use App\Models\StockSignal;
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

    public function generateSignal(string $stockCode, string $stockName): StockSignal
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

            $strength === 3 && !$isBullish =>
                ['SELL', 'STRONG'],

            $strength === 2 && !$isBullish =>
                ['SELL', 'NORMAL'],

            $strength === 1 && !$isBullish =>
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
            'stock_name' => $stockName,

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

        /*
        |--------------------------------------------------------------------------
        | 6. Kirim notifikasi
        |--------------------------------------------------------------------------
        |
        | HOLD tidak mengirim notifikasi karena tidak ada keputusan
        | BUY atau SELL yang perlu diberitahukan.
        |
        */

        if ($signal !== 'HOLD') {
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
        /*
        |--------------------------------------------------------------------------
        | HOLD
        |--------------------------------------------------------------------------
        */

        if ($signal === 'HOLD') {
            return implode(' ', [
                'Tidak ada kondisi BUY atau SELL yang cukup kuat.',
                'MA, RSI, dan volume belum memberikan konfirmasi yang memadai.',
                'Investor disarankan menunggu sampai terdapat kondisi yang lebih jelas sebelum mengambil keputusan.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ringkasan awal
        |--------------------------------------------------------------------------
        */

        $description = "{$signal} {$signalStrength}: {$strength} dari 3 kondisi terpenuhi.";

        /*
        |--------------------------------------------------------------------------
        | Penjelasan Moving Average
        |--------------------------------------------------------------------------
        */

        if ($result['condition_1']) {

            if ($signal === 'BUY') {
                $description .= ' ';
                $description .= 'MA20 berada di atas MA50, yang menunjukkan bahwa ';
                $description .= 'tren harga jangka pendek lebih kuat dibandingkan ';
                $description .= 'tren jangka panjang. Kondisi ini mendukung momentum bullish.';
            } else {
                $description .= ' ';
                $description .= 'MA20 berada di bawah MA50, yang menunjukkan bahwa ';
                $description .= 'tren harga jangka pendek lebih lemah dibandingkan ';
                $description .= 'tren jangka panjang. Kondisi ini mendukung momentum bearish ';
                $description .= 'dan dapat menjadi alasan untuk mengurangi atau menjual posisi.';
            }

        } else {

            if ($signal === 'BUY') {
                $description .= ' ';
                $description .= 'MA20 belum berada pada posisi bullish terhadap MA50, ';
                $description .= 'sehingga tren jangka pendek belum memberikan konfirmasi ';
                $description .= 'kuat untuk pembelian.';
            } else {
                $description .= ' ';
                $description .= 'MA20 belum berada pada posisi bearish terhadap MA50, ';
                $description .= 'sehingga tren belum memberikan konfirmasi kuat untuk penjualan.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Penjelasan RSI
        |--------------------------------------------------------------------------
        */

        if ($result['condition_2']) {

            if ($signal === 'BUY') {
                $description .= ' ';
                $description .= 'RSI berada pada area oversold, ';
                $description .= 'yang menunjukkan tekanan jual relatif tinggi ';
                $description .= 'dan dapat memberikan peluang terjadinya pemulihan harga.';
            } else {
                $description .= ' ';
                $description .= 'RSI berada pada area overbought, ';
                $description .= 'yang menunjukkan bahwa harga telah mengalami tekanan beli ';
                $description .= 'yang cukup tinggi dan berpotensi mengalami koreksi.';
            }

        } else {

            if ($signal === 'BUY') {
                $description .= ' ';
                $description .= 'RSI belum berada pada area oversold, ';
                $description .= 'sehingga indikator momentum belum memberikan konfirmasi tambahan ';
                $description .= 'untuk peluang pembelian.';
            } else {
                $description .= ' ';
                $description .= 'RSI belum berada pada area overbought, ';
                $description .= 'sehingga indikator momentum belum memberikan konfirmasi tambahan ';
                $description .= 'untuk peluang penjualan.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Penjelasan Volume
        |--------------------------------------------------------------------------
        */

        if ($result['condition_3']) {

            if ($signal === 'BUY') {
                $description .= ' ';
                $description .= 'Volume perdagangan meningkat secara signifikan dibandingkan ';
                $description .= 'rata-rata volume sebelumnya, sehingga pergerakan bullish ';
                $description .= 'mendapatkan konfirmasi dari aktivitas perdagangan.';
            } else {
                $description .= ' ';
                $description .= 'Volume perdagangan meningkat secara signifikan dibandingkan ';
                $description .= 'rata-rata volume sebelumnya, sehingga pergerakan bearish ';
                $description .= 'mendapatkan konfirmasi dari aktivitas perdagangan.';
            }

        } else {

            $description .= ' ';
            $description .= 'Volume perdagangan belum menunjukkan peningkatan signifikan ';
            $description .= 'dibandingkan rata-rata sebelumnya, sehingga belum memberikan ';
            $description .= 'konfirmasi tambahan terhadap pergerakan harga.';
        }

        /*
        |--------------------------------------------------------------------------
        | Kesimpulan
        |--------------------------------------------------------------------------
        */

        if ($signal === 'BUY') {

            $description .= ' ';

            if ($signalStrength === 'STRONG') {
                $description .= 'Dengan seluruh kondisi terpenuhi, sistem memberikan ';
                $description .= 'konfirmasi BUY yang kuat.';
            } elseif ($signalStrength === 'NORMAL') {
                $description .= 'Dengan dua kondisi terpenuhi, terdapat dukungan yang cukup ';
                $description .= 'untuk sinyal BUY, meskipun belum seluruh indikator memberikan ';
                $description .= 'konfirmasi.';
            } else {
                $description .= 'Karena hanya satu kondisi yang terpenuhi, sinyal BUY ';
                $description .= 'masih tergolong lemah dan sebaiknya tidak dianggap sebagai ';
                $description .= 'konfirmasi pembelian yang kuat.';
            }

        } else {

            $description .= ' ';

            if ($signalStrength === 'STRONG') {
                $description .= 'Dengan seluruh kondisi terpenuhi, sistem memberikan ';
                $description .= 'konfirmasi SELL yang kuat. Kondisi ini menunjukkan bahwa ';
                $description .= 'tekanan bearish cukup dominan dan menjual atau mengurangi ';
                $description .= 'posisi dapat dipertimbangkan.';
            } elseif ($signalStrength === 'NORMAL') {
                $description .= 'Dengan dua kondisi terpenuhi, terdapat dukungan yang cukup ';
                $description .= 'untuk sinyal SELL, meskipun belum seluruh indikator memberikan ';
                $description .= 'konfirmasi.';
            } else {
                $description .= 'Karena hanya satu kondisi yang terpenuhi, sinyal SELL ';
                $description .= 'masih tergolong lemah dan sebaiknya tidak dianggap sebagai ';
                $description .= 'konfirmasi penjualan yang kuat.';
            }
        }

        return $description;
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