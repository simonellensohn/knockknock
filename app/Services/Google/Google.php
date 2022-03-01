<?php

namespace App\Services\Google;

use App\Services\Google\Resources\CalendarResource;
use App\Services\Google\Resources\SheetsResource;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Sheets;

class Google
{
    public function __construct(
        public readonly Client $client
    ) {}

    public function sheets(): SheetsResource
    {
        return new SheetsResource(new Sheets($this->client));
    }

    public function calendar(): CalendarResource
    {
        return new CalendarResource(new Calendar($this->client));
    }
}
