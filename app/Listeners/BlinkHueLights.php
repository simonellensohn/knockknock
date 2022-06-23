<?php

namespace App\Listeners;

use App\Events\BellRinging;
use App\Services\Hue\HueService;

class BlinkHueLights
{
    public function __construct(
        public HueService $service
    ) {
    }

    public function handle(BellRinging $event): void
    {
        $this->service->light()->blinkAll();
    }
}
