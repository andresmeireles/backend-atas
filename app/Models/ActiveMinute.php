<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ActiveMinute
 *
 * @property int $id
 * @property int $minute_id
 * @property string $created_at
 * @property-read \App\Models\Minute $minute
 * @method static \Database\Factories\ActiveMinuteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActiveMinute whereMinuteId($value)
 * @mixin \Eloquent
 */
class ActiveMinute extends Model
{
    use HasFactory;

    protected $fillable = [
        'minute_id'
    ];

    public $timestamps = false;

    /**
     * Returns the Minute model that belongs to this object.
     *
     * @return BelongsTo<Minute, ActiveMinute>
     */
    public function minute(): BelongsTo
    {
        return $this->belongsTo(Minute::class);
    }

    /**
     * Retrieves a collection of active expired minutes.
     *
     * @return Collection<int, ActiveMinute>
     */
    public function getActiveExpiredMinutes(): Collection
    {
        return $this->join('minutes', 'minutes.id', '=', 'active_minutes.minute_id')
            ->whereDate('minutes.date', '<', now())
            ->get();
    }
}
