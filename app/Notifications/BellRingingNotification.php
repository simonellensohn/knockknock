<?php

namespace App\Notifications;

use App\Models\Ring;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BellRingingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ring $ring
    ) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => '🔔 Digga, es klingelt!',
            'body' => '🏃 Run forest, run!',
            'created' => now()->toIso8601String(),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('🔔 Digga, es klingelt!')
            ->icon('/notification-icon.png')
            ->body('🏃 Run forest, run!')
            ->action('View app', 'view_app')
            ->data(['id' => $notification->id]);
    }
}
