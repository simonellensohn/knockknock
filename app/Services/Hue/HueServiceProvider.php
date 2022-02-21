<?php

namespace App\Services\Hue;

use Illuminate\Support\ServiceProvider;

class HueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            HueService::class,
            fn () => new HueService(
                baseUrl: config('services.hue.endpoint'),
                appId: config('services.hue.app_id'),
                clientId: config('services.hue.client_id'),
                clientSecret: config('services.hue.client_secret'),
                username: config('services.hue.username'),
            )
        );
    }
}
