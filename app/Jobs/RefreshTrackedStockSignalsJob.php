<?php

namespace App\Jobs;

use App\Services\StockPriceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshTrackedStockSignalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(StockPriceProvider $provider): void
    {
        $provider->importLatestPricesForAllStocks(50);

        SendStockSignalsJob::dispatchSync();
    }
}