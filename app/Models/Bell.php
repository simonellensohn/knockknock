<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bell extends Model
{
    use HasFactory;

    public $casts = [
        'active' => 'boolean',
        'threshold' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rings(): HasMany
    {
        return $this->hasMany(Ring::class);
    }

    public function ring(float $volume, array $events): Ring
    {
        return $this->rings()->create(['volume' => $volume, 'events' => $events]);
    }
}
