<?php

namespace App\Http\Controllers;

use App\Jobs\SendStockSignalsJob;
use App\Models\AutomationSetting;
use App\Services\StockPriceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutomationController extends Controller
{
    /**
     * START automation.
     */
    public function start()
    {
        $setting = AutomationSetting::firstOrCreate(
            ['id' => 1],
            ['enabled' => false]
        );

        $setting->update([
            'enabled' => true,
        ]);

        return back()->with(
            'success',
            'Automation berhasil diaktifkan.'
        );
    }

    /**
     * STOP automation.
     */
    public function stop()
    {
        $setting = AutomationSetting::firstOrCreate(
            ['id' => 1],
            ['enabled' => false]
        );

        $setting->update([
            'enabled' => false,
        ]);

        return back()->with(
            'success',
            'Automation berhasil dihentikan.'
        );
    }

    /**
     * Dipanggil external cron secara berkala.
     */
    public function run(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SECRET TOKEN
        |--------------------------------------------------------------------------
        */

        $token = $request->query('token');

        if (
            !$token ||
            !hash_equals(
                (string) config('services.automation.token'),
                (string) $token
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK AUTOMATION
        |--------------------------------------------------------------------------
        */

        $setting = AutomationSetting::firstOrCreate(
            ['id' => 1],
            ['enabled' => false]
        );

        if (!$setting->enabled) {
            return response()->json([
                'status' => 'off',
                'message' => 'Automation is disabled.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TIMEZONE
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | WEEKDAY ONLY
        |--------------------------------------------------------------------------
        */

        if ($now->isWeekend()) {
            return response()->json([
                'status' => 'skip',
                'message' => 'Weekend.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT CONCURRENT REQUESTS
        |--------------------------------------------------------------------------
        */

        $lock = Cache::lock(
            'stock-signal-automation',
            600
        );

        if (!$lock->get()) {
            return response()->json([
                'status' => 'skip',
                'message' => 'Another automation process is running.',
            ]);
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | REFRESH SETTING
            |--------------------------------------------------------------------------
            */

            $setting->refresh();

            if (!$setting->enabled) {
                return response()->json([
                    'status' => 'off',
                    'message' => 'Automation was stopped.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | IMPORT PRICE - 17:30
            |--------------------------------------------------------------------------
            */

            if (
                $now->format('H:i') >= '16:55' &&
                (!$setting->last_import_date ||
                    $setting->last_import_date->format('Y-m-d') !== $now->format('Y-m-d'))
            ) {

                Log::info('Automation: starting stock price import.');

                $provider = new StockPriceProvider();

                $provider->importLatestPricesForAllStocks(50);

                $setting->update([
                    'last_import_date' => $now->toDateString(),
                ]);

                Log::info('Automation: stock price import completed.');
            }


            /*
            |--------------------------------------------------------------------------
            | GENERATE SIGNAL - 17:35
            |--------------------------------------------------------------------------
            */

            if (
                $now->format('H:i') >= '16:57' &&
                (!$setting->last_signal_date ||
                    $setting->last_signal_date->format('Y-m-d') !== $now->format('Y-m-d'))
            ) {

                Log::info('Automation: starting signal generation.');

                /*
                 * dispatchSync() sengaja digunakan.
                 * Tidak membutuhkan queue:work.
                 */
                SendStockSignalsJob::dispatchSync();

                $setting->update([
                    'last_signal_date' => $now->toDateString(),
                ]);

                Log::info('Automation: signal generation completed.');
            }


            return response()->json([
                'status' => 'ok',
                'enabled' => true,
                'time' => $now->format('Y-m-d H:i:s'),
                'last_import_date' => $setting->last_import_date?->format('Y-m-d'),
                'last_signal_date' => $setting->last_signal_date?->format('Y-m-d'),
            ]);

        } catch (\Throwable $e) {

            Log::error('Stock automation failed.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);

        } finally {

            $lock->release();
        }
    }
}
