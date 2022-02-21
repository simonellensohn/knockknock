<?php

namespace App\Services\Concerns;

use Closure;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;

trait CanBeFaked
{
    /**
     * Proxy Fake request call through to Http::fake().
     */
    public static function fake(null|Closure|array $callback = null): Factory
    {
        return Http::fake(callback: $callback);
    }
}
