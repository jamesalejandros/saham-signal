<?php

use App\Jobs\SendStockSignalsJob;
use App\Services\StockPriceProvider;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $provider = new StockPriceProvider;
    $provider->importLatestPricesForAllStocks(50);
})
    ->dailyAt('17:30')
    ->timezone('Asia/Jakarta')
    ->weekdays();

Schedule::job(new SendStockSignalsJob())
    ->dailyAt('17:35')
    ->timezone('Asia/Jakarta')
    ->weekdays();