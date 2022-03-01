<?php

namespace App\Services\Google\Resources;

use App\Services\Google\Exceptions\SheetsException;
use Google\Service\Sheets;

class SheetsResource
{
    public function __construct(
        private readonly Sheets $service,
    ) {
    }

    public function values(string $sheetId, string $range): array
    {
        $response = $this->service->spreadsheets_values->get($sheetId, $range);

        if (! $response->valid()) {
            throw new SheetsException();
        }

        return $response->getValues();
    }
}
