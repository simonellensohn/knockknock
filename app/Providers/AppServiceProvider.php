<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Model::unguard();

        if ($this->app->runningUnitTests()) {
            $this->app->register(ParallelTestingServiceProvider::class);

        }
    }

    public function boot(): void
    {
        //
    }
}
