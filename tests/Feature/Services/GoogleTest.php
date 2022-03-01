<?php

use App\Services\Google\Resources\CalendarResource;
use App\Services\Google\Resources\SheetsResource;
use Google\Service\Calendar;
use Google\Service\Calendar\Events;
use Google\Service\Sheets;
use Google\Service\Sheets\Resource\Spreadsheets;
use Google\Service\Sheets\ValueRange;
use function Pest\Laravel\mock;
use function Pest\Laravel\partialMock;

test('it fetches sheets values from the google sdk', function () {
    $mock = mock(Sheets::class);
    $mock->spreadsheets_values = partialMock(Spreadsheets::class);
    $mock->spreadsheets_values
        ->shouldReceive('get')
        ->andReturn(new ValueRange(['values' => [['a']]]));

    $response = app(SheetsResource::class)->values(1, 'A2:B');

    expect($response)
        ->toBeArray()
        ->toContain(['a']);
});

test('it fetches announcements from the google sdk', function () {
    $mock = mock(Calendar::class);
    $mock->events = partialMock(Events::class);
    $mock->events
        ->shouldReceive('listEvents')
        ->andReturn(new Events(['items' => [new Calendar\Event()]]));

    $response = app(CalendarResource::class)->announcements(1);

    expect($response)
        ->toBeArray()
        ->each->toBeInstanceOf(Calendar\Event::class);
});
