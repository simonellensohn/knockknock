<?php

namespace App\Services\Hue;

use Illuminate\Support\ServiceProvider;

class HueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HueApi::class, function () {
            return new HueApi([
                'baseUrl' => config('services.hue.endpoint'),
                'applicationKey' => config('services.hue.key'),
            ]);
        });
    }
}
