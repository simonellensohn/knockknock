<?php

namespace App\Listeners;

use App\Events\RingCreated;
use App\Services\Hue\HueApi;

class BlinkHueLights
{
    public function __construct(
        public HueApi $hue
    ) {}

    public function handle(RingCreated $event): void
    {
        $this->hue->blinkAllLights();
    }
}
