<?php

use App\Events\BellRinging;
use App\Listeners\BlinkHueLights;
use App\Models\Ring;
use App\Services\Hue\HueService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

test('it listens to the bell ringing event', function () {
    Event::fake();

    Event::assertListening(
        BellRinging::class,
        BlinkHueLights::class
    );
});

test('it blinks all hue lights', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $ring = Ring::factory()->create();
    $event = new BellRinging($ring->bell, $ring);
    $listener = resolve(BlinkHueLights::class);
    $fake = HueService::fake([
        '/route/api/*/groups/*/action' => Http::response(status: Response::HTTP_OK),
    ]);

    $listener->handle($event);

    $fake->assertSentCount(1);
});
