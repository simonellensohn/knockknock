<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ring extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function ($user) {
            //
        });
    }

    public function bell(): BelongsTo
    {
        return $this->belongsTo(Bell::class);
    }
}
