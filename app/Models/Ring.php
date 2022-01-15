<?php

namespace App\Models;

use App\Events\RingCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ring extends Model
{
    use HasFactory;

    protected $casts = [
        'volume' => 'float',
        'events' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => RingCreated::class,
    ];

    public function bell(): BelongsTo
    {
        return $this->belongsTo(Bell::class);
    }
}
