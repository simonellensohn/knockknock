<?php

namespace App\Providers;

use App\Actions\CreateBell;
use App\Actions\CreateUser;
use App\Actions\UpdateBell;
use App\Actions\UpdateUser;
use App\Contracts\Actions\CreatesBell;
use App\Contracts\Actions\CreatesUser;
use App\Contracts\Actions\UpdatesBell;
use App\Contracts\Actions\UpdatesUser;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        CreatesBell::class => CreateBell::class,
        UpdatesBell::class => UpdateBell::class,
        CreatesUser::class => CreateUser::class,
        UpdatesUser::class => UpdateUser::class,
    ];
}
