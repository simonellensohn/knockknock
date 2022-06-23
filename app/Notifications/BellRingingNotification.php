<?php

namespace App\Notifications;

use App\Models\Ring;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BellRingingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ring $ring
    ) {
    }

    public function via(User $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray(User $notifiable): array
    {
        return [
            'title' => '🔔 Digga, es klingelt!',
            'body' => '🏃 Run forest, run!',
            'created' => now()->toIso8601String(),
        ];
    }

    public function toWebPush(User $notifiable, self $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('🔔 Digga, es klingelt!')
            ->icon('/notification-icon.png')
            ->body('🏃 Run forest, run!')
            ->action('View app', 'view_app')
            ->data(['id' => $notification->id]);
    }
}
