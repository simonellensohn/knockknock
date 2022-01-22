<?php

namespace App\Models;

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

    public function bell(): BelongsTo
    {
        return $this->belongsTo(Bell::class);
    }
}
