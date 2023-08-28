<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\MeetItem
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property object{is_repeatable: bool, is_obligatory: bool}|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MeetType> $types
 * @property-read int|null $types_count
 * @method static \Database\Factories\MeetItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetItem whereType($value)
 * @mixin \Eloquent
 */
class MeetItem extends Model
{
    use HasFactory;

    public const CALL_TYPE = 'call';
    public const SIMPLE_TEXT_TYPE = 'simple_text';
    public const HYMN_TYPE = 'hymn';

    public const TYPES = [self::CALL_TYPE, self::SIMPLE_TEXT_TYPE, self::HYMN_TYPE];

    protected $fillable = [
        'name',
        'type'
    ];

    /** @var array<int, string> */
    protected $appends = [
        'is_repeatable',
        'is_obligatory',
    ];

    protected $hidden = [
        'pivot'
    ];

    public $timestamps = false;

    /**
     * @return BelongsToMany<MeetType>
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(MeetType::class, 'meet_type_items');
    }

    /** @return Attribute<?bool, never> */
    public function isRepeatable(): Attribute
    {
        return new Attribute(
            get: fn () => $this->pivot?->is_repeatable
        );
    }

    /** @return Attribute<?bool, never> */
    public function isObligatory(): Attribute
    {
        return new Attribute(
            get: fn () => $this->pivot?->is_obligatory
        );
    }
}
