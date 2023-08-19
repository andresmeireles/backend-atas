<?php

namespace App\Models;

use App\Service\Minute\Meeting\MeetStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Minute extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'code',
        'schema',
        'status',
        'meet_type_id',
    ];

    /** @var mixed[] */
    protected $appends = ['assignments'];

    protected $hidden = [
        'calls',
        'hymns',
        'simpleTexts'
    ];

    public $timestamps = false;

    /**
     * @return HasOne<ActiveMinute>
     */
    public function activeMinute(): HasOne
    {
        return $this->hasOne(ActiveMinute::class);
    }

    /** @return BelongsTo<MeetType> */
    public function meetType(): BelongsTo
    {
        return $this->belongsTo(MeetType::class);
    }

    /**
     * @return HasMany<AssignmentCall>
     */
    public function calls(): HasMany
    {
        return $this->hasMany(AssignmentCall::class);
    }

    /**
     * @return HasMany<AssignmentHymn>
     */
    public function hymns(): HasMany
    {
        return $this->hasMany(AssignmentHymn::class);
    }

    /**
     * @return HasMany<AssignmentSimpleText>
     */
    public function simpleTexts(): HasMany
    {
        return $this->hasMany(AssignmentSimpleText::class);
    }

    /**
     *  @return Attribute<User, never>
     */
    public function userId(): Attribute
    {
        return Attribute::make(get: fn (int $value) => User::find($value));
    }

    /**
     * @return Attribute<Collection<int, AssignmentCall|AssignmentHymn|AssignmentSimpleText>, never>
     */
    public function assignments(): Attribute
    {
        return new Attribute(
            fn () => [...$this->calls, ...$this->hymns, ...$this->simpleTexts]
        );
    }

    /**
     * @return HasOne<MeetType>
     */
    public function meetingType(): HasOne
    {
        return $this->hasOne(MeetType::class);
    }

    public function expire(): self
    {
        $this->status = MeetStatus::EXPIRED->value;
        $this->save();
        return $this;
    }
}
