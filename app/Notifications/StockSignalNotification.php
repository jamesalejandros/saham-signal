<?php

namespace App\Notifications;

use App\Models\StockSignal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;


class StockSignalNotification extends Notification
{
    use Queueable;

    public function __construct(
        public StockSignal $stockSignal
    ) {
    }


    public function via(object $notifiable): array
    {
        $channels = [
            'database',
            WebPushChannel::class,
        ];

        if (!empty($notifiable->telegram_chat_id)) {
            $channels[] = 'telegram';
        }

        return $channels;
    }

    public function toDatabase(object $notifiable): array
    {
        $stockName = $this->stockSignal->stock?->stock_name ?? 'Unknown stock';

        return [
            'stock_code' => $this->stockSignal->stock_code,
            'stock_name' => $stockName,
            'signal' => $this->stockSignal->signal,

            'signal_strength' => $this->stockSignal->signal_strength,

            'description' => $this->stockSignal->description,
        ];
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $stockName = $this->stockSignal->stock?->stock_name ?? 'Unknown stock';
        $message =
            "📈 STOCK SIGNAL\n\n" .
            "Stock: {$this->stockSignal->stock_code}\n" .
            "Name: {$stockName}\n" .
            "Signal: {$this->stockSignal->signal}\n" .
            "Strength: {$this->stockSignal->signal_strength}\n\n" .
            "{$this->stockSignal->description}";

        return TelegramMessage::create()
            ->content($message);
    }

    public function toWebPush(
        object $notifiable,
        $notification
    ): WebPushMessage {
        $stockName = $this->stockSignal->stock?->stock_name
            ?? 'Unknown stock';

        return (new WebPushMessage)
            ->title(
                "{$this->stockSignal->signal} {$this->stockSignal->stock_code}"
            )
            ->body(
                "{$stockName} • {$this->stockSignal->signal_strength}"
            )
            ->icon('/icons/icon-192.png')
            ->badge('/icons/badge-72.png')
            ->data([
                'signal_id' => $this->stockSignal->id,
                'stock_code' => $this->stockSignal->stock_code,
                'url' => route(
                    'signals.show',
                    $this->stockSignal
                ),
            ])
            ->tag(
                'stock-signal-' . $this->stockSignal->stock_code
            )
            ->options([
                'TTL' => 3600,
            ]);
    }

}
