<?php

namespace App\Listeners;

use App\Events\RingCreated;
use App\Models\User;
use App\Notifications\BellRinging;
use Illuminate\Support\Facades\Notification;

class SendBellRingingNotification
{
    public function handle(RingCreated $event): void
    {
        $users = User::all();

        Notification::send($users, new BellRinging($event->ring));
    }
}
