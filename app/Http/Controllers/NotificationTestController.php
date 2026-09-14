<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StockSignal;
use App\Notifications\StockSignalNotification;

class NotificationTestController extends Controller
{
    public function send()
    {
        // Buat dummy stock signal
        $signal = StockSignal::create([
            'stock_code' => 'BBCA',
            'stock_name' => 'Bank Central Asia',
            'condition_1' => true,
            'condition_2' => true,
            'condition_3' => false,
            'signal' => 'BUY',
            'signal_strength' => 'NORMAL',
            'description' => '2 dari 3 kondisi terpenuhi.',
        ]);

        // Ambil semua user
        $users = User::all();

        // Kirim notification ke semua user
        foreach ($users as $user) {
            $user->notify(
                new StockSignalNotification($signal)
            );
        }

        return back()->with(
            'success',
            "Notification berhasil dikirim ke {$users->count()} user."
        );
    }
}

// $user->notify(
//             new StockSignalNotification($signal)
//         );