<?php

namespace App\Notifications;

use App\Models\StockSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramMessage;


class StockSignalTelegramNotification extends Notification
{
    use Queueable;

    public function __construct(
        public StockSignal $stockSignal
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'telegram',
        ];
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $signalId = (string)$this->stockSignal->id;
        $appUrl ="http:://localhost/signals/".$signalId;
        Log::info("Signal generated for {$appUrl}");
        $message =
            "📈 STOCK SIGNAL\n\n" .
            "Stock: {$this->stockSignal->stock_code}\n" .
            "Name: {$this->stockSignal->stock_name}\n" .
            "Signal: {$this->stockSignal->signal}\n" .
            "Strength: {$this->stockSignal->signal_strength}\n\n" .
            "details : {$appUrl}";
            

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message);
    }
}
