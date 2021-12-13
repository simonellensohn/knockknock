<?php

namespace App\Models;

use App\Services\Hue\HueApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ring extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (self $ring) {
            app(HueApi::class)->blinkAllLights();
        });
    }

    public function bell(): BelongsTo
    {
        return $this->belongsTo(Bell::class);
    }
}
