<?php

namespace App\Notifications;

use App\Models\StockSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
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
        $message =
            "📈 STOCK SIGNAL\n\n" .
            "Stock: {$this->stockSignal->stock_code}\n" .
            "Name: {$this->stockSignal->stock_name}\n" .
            "Signal: {$this->stockSignal->signal}\n" .
            "Strength: {$this->stockSignal->signal_strength}\n\n" .
            "{$this->stockSignal->description}";

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message);
    }
}
