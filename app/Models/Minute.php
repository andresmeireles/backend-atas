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

/**
 * App\Models\Minute
 *
 * @property int $id
 * @property string $date
 * @property int $user_id
 * @property string $created_at
 * @property bool $active
 * @property string $code
 * @property string $schema
 * @property string $status
 * @property int|null $meet_type_id
 * @property-read \App\Models\ActiveMinute|null $activeMinute
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentCall> $calls
 * @property-read int|null $calls_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentHymn> $hymns
 * @property-read int|null $hymns_count
 * @property-read \App\Models\MeetType|null $meetType
 * @property-read \App\Models\MeetType|null $meetingType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentSimpleText> $simpleTexts
 * @property-read int|null $simple_texts_count
 * @method static \Database\Factories\MinuteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Minute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Minute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Minute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereMeetTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereSchema($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Minute whereUserId($value)
 * @mixin \Eloquent
 */
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

    /** @return BelongsTo<MeetType, Minute> */
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
