<?php

namespace App\Services\Google;

use Google\Client;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            'google',
            fn () => new Google(new Client())
        );
    }
}
