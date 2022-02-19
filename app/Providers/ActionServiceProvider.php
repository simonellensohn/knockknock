<?php

namespace App\Providers;

use App\Actions\CreateBell;
use App\Actions\UpdateBell;
use App\Contracts\Actions\CreatesBell;
use App\Contracts\Actions\UpdatesBell;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        CreatesBell::class => CreateBell::class,
        UpdatesBell::class => UpdateBell::class,
    ];
}
