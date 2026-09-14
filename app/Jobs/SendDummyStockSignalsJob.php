<?php

namespace App\Jobs;

use App\Services\StockSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDummyStockSignalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $count = 5,
    ) {}

    public function handle(StockSignalService $service): void
    {
        $dummyStocks = [
            ['code' => 'BBCA', 'name' => 'Bank Central Asia Tbk'],
            ['code' => 'BBRI', 'name' => 'Bank Rakyat Indonesia Tbk'],
            ['code' => 'TLKM', 'name' => 'Telkom Indonesia Tbk'],
            ['code' => 'ASII', 'name' => 'Astra International Tbk'],
            ['code' => 'UNVR', 'name' => 'Unilever Indonesia Tbk'],
            ['code' => 'GOTO', 'name' => 'GoTo Gojek Tokopedia Tbk'],
            ['code' => 'BMRI', 'name' => 'Bank Mandiri Tbk'],
            ['code' => 'ANTM', 'name' => 'Aneka Tambang Tbk'],
        ];

        $picked = collect($dummyStocks)->shuffle()->take($this->count);

        foreach ($picked as $stock) {
            $service->generateSignal($stock['code'], $stock['name']);
        }

        Log::info("Dummy batch complete: {$picked->count()} signals generated.");
    }
}