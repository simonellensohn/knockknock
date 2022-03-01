<?php

namespace App\Services\Google\Facades;

use Illuminate\Support\Facades\Facade;

class Google extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'google';
    }
}
