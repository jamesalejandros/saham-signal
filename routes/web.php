<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationTestController;
use App\Http\Controllers\StockSignalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserStockSignalController;
use App\Http\Controllers\AutomationController;

Route::middleware('auth')->group(function () {

    Route::get(
        '/user/stocks',
        [UserStockSignalController::class, 'index']
    )->name('user.stocks.index');

    Route::put(
        '/user/stocks',
        [UserStockSignalController::class, 'update']
    )->name('user.stocks.update');

    Route::post(
        '/user/stocks',
        [UserStockSignalController::class, 'store']
    )->name('user.stocks.store');

    Route::delete(
        '/user/stocks/{stockCode}',
        [UserStockSignalController::class, 'destroy']
    )->name('user.stocks.destroy');

    Route::get(
        '/user/stocks/selected',
        [UserStockSignalController::class, 'selected']
    )->name('user.stocks.selected');

});



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [StockSignalController::class, 'dashboard'])
        ->middleware(['auth'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Stock Signals
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat daftar signal
    Route::get(
        '/signals',
        [StockSignalController::class, 'index']
    )->name('signals.index');


    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Stock Signal Generation
        |--------------------------------------------------------------------------
        */

        // Harus diletakkan SEBELUM /signals/{signal}
        Route::get(
            '/signals/create',
            [StockSignalController::class, 'create']
        )->name('signals.create');

        Route::post(
            '/signals',
            [StockSignalController::class, 'store']
        )->name('signals.store');

        Route::post(
            '/automation/start',
            [AutomationController::class, 'start']
        )->name('automation.start');

        Route::post(
            '/automation/stop',
            [AutomationController::class, 'stop']
        )->name('automation.stop');



        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'users',
            UserController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Notification Testing
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notification-test',
            [NotificationTestController::class, 'send']
        )->name('notification.test');


    });


    /*
    |--------------------------------------------------------------------------
    | Stock Signal Detail
    |--------------------------------------------------------------------------
    */

    // Letakkan setelah /signals/create
    // Semua user yang login boleh melihat detail
    Route::get(
        '/signals/{signal}',
        [StockSignalController::class, 'show']
    )->name('signals.show');


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/{id}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllAsRead');




    // Route::get(
    //     '/telegram-test',
    //     function () {

    //         \Illuminate\Support\Facades\Notification::route(
    //             'telegram',
    //             config('services.telegram-bot-api.chat_id')
    //         )->notify(
    //                 new \App\Notifications\StockSignalTelegramNotification(
    //                     \App\Models\StockSignal::latest()->first()
    //                 )
    //             );

    //         return 'Telegram test berhasil dikirim.';
    //     }
    // )->name('telegram.test');


});

Route::get(
    '/automation/run',
    [AutomationController::class, 'run']
)->name('automation.run');



/*
|--------------------------------------------------------------------------
| Breeze Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
