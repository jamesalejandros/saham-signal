<?php

use Illuminate\Http\Request;
use App\Http\Controllers\StockSignalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/news', [StockSignalController::class, 'news'])->name('signals.news');
// for stock info
Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
Route::patch('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');