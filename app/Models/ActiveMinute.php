<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
