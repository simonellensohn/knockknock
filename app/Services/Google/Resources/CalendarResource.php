<?php

namespace App\Services\Google\Resources;

use App\Services\Google\Exceptions\CalendarException;
use Google\Service\Calendar;

class CalendarResource
{
    public function __construct(
        private readonly Calendar $service,
    ) {}

    /**
     * @return array<Calendar\Event>
     */
    public function announcements(string $calendarId): array
    {
        $response = $this->service->events->listEvents($calendarId);

        if (! $response->valid()) {
            throw new CalendarException();
        }

        return $response->getItems();
    }
}
