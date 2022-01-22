<?php

namespace App\Listeners;

use App\Events\BellRinging;
use App\Models\User;
use App\Notifications\BellRingingNotification;
use Illuminate\Support\Facades\Notification;

class SendBellRingingNotification
{
    public function handle(BellRinging $event): void
    {
        $users = User::all();

        Notification::send($users, new BellRingingNotification($event->ring));
    }
}
