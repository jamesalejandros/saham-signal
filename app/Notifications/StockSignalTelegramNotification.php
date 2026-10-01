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
        $signalId = (string) $this->stockSignal->id;

        $appUrl = "https://sahamsignal.ct.ws/signals/{$signalId}";

        $stockCode = $this->stockSignal->stock_code;
        $stockName = $this->stockSignal->stock?->stock_name ?? 'Unknown stock';

        $stockbitUrl = "https://stockbit.com/symbol/{$stockCode}";

        Log::info("Signal generated for {$appUrl}");

        $message =
            "📈 <b>STOCK SIGNAL</b>\n\n" .
            "Stock: {$stockCode}\n" .
            "Name: {$stockName}\n" .
            "Signal: {$this->stockSignal->signal}\n" .
            "Strength: {$this->stockSignal->signal_strength}\n\n" .
            "🔗 <a href=\"{$appUrl}\">View Signal Details</a>\n" .
            "📊 <a href=\"{$stockbitUrl}\">View on Stockbit</a>";

        return TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->content($message)
            ->options([
                'parse_mode' => 'HTML',
            ]);
    }
}