<?php

namespace App\Listeners;

use App\Events\RingCreated;
use App\Models\User;
use App\Notifications\BellRinging;
use App\Services\Hue\HueApi;
use Illuminate\Support\Facades\Notification;

class SendBellRingingNotification
{
    public function handle(RingCreated $event)
    {
        $users = User::all();

        Notification::send($users, new BellRinging($event->ring));
    }
}
