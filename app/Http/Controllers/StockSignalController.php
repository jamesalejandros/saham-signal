<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StockSignal;
use App\Notifications\StockSignalNotification;
use App\Notifications\StockSignalTelegramNotification;
use App\Services\StockSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class StockSignalController extends Controller
{
    public function index()
    {
        $signals = StockSignal::latest()->get();

        return view(
            'signals.index',
            compact('signals')
        );
    }

    public function create()
    {
        return view('signals.create');
    }

    public function store(
        Request $request,
        StockSignalService $service
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'stock_code' => [
                'required',
                'string',
                'max:20',
            ],

            'stock_name' => [
                'required',
                'string',
                'max:255',
            ],

            'condition_1' => [
                'nullable',
                'boolean',
            ],

            'condition_2' => [
                'nullable',
                'boolean',
            ],

            'condition_3' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Stock Signal
        |--------------------------------------------------------------------------
        */

        $signal = $service->generateSignal(
            stockCode: $validated['stock_code'],
            stockName: $validated['stock_name'],

            condition1: (bool) ($validated['condition_1'] ?? false),

            condition2: (bool) ($validated['condition_2'] ?? false),

            condition3: (bool) ($validated['condition_3'] ?? false),
        );


        // /*
        // |--------------------------------------------------------------------------
        // | Web Notification
        // |--------------------------------------------------------------------------
        // |
        // | Kirim notification ke SEMUA user dengan role "user".
        // |
        // */

        // $users = User::role('user')->get();

        // foreach ($users as $user) {

        //     $user->notify(
        //         new StockSignalNotification($signal)
        //     );

        // }


        // /*
        // |--------------------------------------------------------------------------
        // | Telegram Group Notification
        // |--------------------------------------------------------------------------
        // |
        // | Kirim SATU kali ke Telegram Group.
        // |
        // */

        // Notification::route(
        //     'telegram',
        //     config('services.telegram-bot-api.chat_id')
        // )->notify(
        //     new StockSignalTelegramNotification($signal)
        // );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('signals.index')
            ->with(
                'success',
                'Signal berhasil dibuat. Notification dikirim ke website dan Telegram Group.'
            );
    }

    public function show(StockSignal $signal)
{
    $prices = \App\Models\StockPrice::where('stock_code', $signal->stock_code)
        ->orderBy('date')
        ->latest('date') // newest first to grab the most recent 30...
        ->take(50)
        ->get()
        ->sortBy('date') // ...then re-sort oldest-first for the chart's x-axis
        ->values();

    return view('signals.show', compact('signal', 'prices'));
}
}
