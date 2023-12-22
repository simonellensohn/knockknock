<?php

namespace App\Models;

use App\Events\BellRinging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Bell.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property float $min_volume
 * @property float $max_volume
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ring[] $rings
 * @property-read int|null $rings_count
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\BellFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bell query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereMaxVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereMinVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bell whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Bell extends Model
{
    use HasFactory;

    public $casts = [
        'active' => 'boolean',
        'min_volume' => 'float',
        'max_volume' => 'float',
    ];

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Ring>
     */
    public function rings(): HasMany
    {
        return $this->hasMany(Ring::class);
    }

    public function ring(float $volume, array $events): Ring
    {
        $ring = $this->rings()->create([
            'volume' => $volume,
            'events' => $events,
            'triggered' => $this->active,
        ]);

        BellRinging::dispatchIf($this->active, $this, $ring);

        return $ring;
    }
}
