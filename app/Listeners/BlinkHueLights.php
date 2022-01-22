<?php

namespace App\Listeners;

use App\Events\BellRinging;
use App\Services\Hue\HueApi;

class BlinkHueLights
{
    public function __construct(
        public HueApi $hue
    ) {}

    public function handle(BellRinging $event): void
    {
        $this->hue->blinkAllLights();
    }
}
