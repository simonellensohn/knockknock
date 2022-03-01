<?php

use App\Services\Google\Facades\Google;
use Google\Service\Calendar\Event;

it('can get sheet values', function () {
    Google::shouldReceive('sheets->values')
        ->once()
        ->andReturn([['A', 'B']]);

    $sheets = Google::sheets()->values(1, 'A2:B');

    expect($sheets)
        ->toBeArray()
        ->toContain(['A', 'B']);
});

it('can get announcements', function () {
    Google::shouldReceive('calendar->announcements')
        ->once()
        ->andReturn([new Event()]);

    $announcements = Google::calendar()->announcements(1);

    expect($announcements)
        ->toBeArray()
        ->each->toBeInstanceOf(Event::class);
});
