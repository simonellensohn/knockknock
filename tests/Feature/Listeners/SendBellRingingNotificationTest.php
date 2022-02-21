<?php

use App\Events\BellRinging;
use App\Listeners\SendBellRingingNotification;
use App\Models\Ring;
use App\Models\User;
use App\Notifications\BellRingingNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushMessage;

test('it listens to the bell ringing event', function () {
    Event::fake();

    Event::assertListening(
        BellRinging::class,
        SendBellRingingNotification::class
    );
});

test('it notifies all users when the lights are blinking', function () {
    Notification::fake();
    $ring = Ring::factory()->create();
    $event = new BellRinging($ring->bell, $ring);
    $users = User::factory()->count(2)->create();
    $listener = resolve(SendBellRingingNotification::class);

    $listener->handle($event);

    Notification::assertSentTo($users, function (BellRingingNotification $notification) use ($ring) {
        $array = $notification->toArray(new User());
        $webPush = $notification->toWebPush(new User(), $notification);

        return $notification->ring->is($ring)
            && expect($array)->toHaveKeys(['title', 'body', 'created'])
            && expect($webPush)->toBeInstanceOf(WebPushMessage::class);
    });
});
