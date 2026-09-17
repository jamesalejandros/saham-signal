<?php

namespace App\Jobs;

use App\Models\Stock;
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
        Stock::query()->update([
            'summary' => null,
            'summary_updated' => null,
        ]);

        $stocks = Stock::query()
            ->inRandomOrder()
            ->take($this->count)
            ->get();

        foreach ($stocks as $stock) {
            $service->generateSignal($stock->stock_code);
        }

        Log::info("Stock batch complete: {$stocks->count()} signals generated.");
    }
}