<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Ring.
 *
 * @property int $id
 * @property int $bell_id
 * @property float $volume
 * @property array|null $events
 * @property int $triggered
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bell|null $bell
 *
 * @method static \Database\Factories\RingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ring newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ring query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereBellId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereTriggered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ring whereVolume($value)
 *
 * @mixin \Eloquent
 */
class Ring extends Model
{
    use HasFactory;

    protected $casts = [
        'volume' => 'float',
        'events' => 'array',
    ];

    /**
     * @return BelongsTo<Bell, self>
     */
    public function bell(): BelongsTo
    {
        return $this->belongsTo(Bell::class);
    }
}
