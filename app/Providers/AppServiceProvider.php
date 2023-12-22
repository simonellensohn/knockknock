<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Model::unguard();

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Pulse::users(function (Collection $ids) {
            return User::query()
                ->select(['id', 'first_name', 'last_name', 'email'])
                ->findMany($ids)
                ->map(fn (User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'extra' => $user->email,
                ]);
        });
    }
}
