<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\Minute\Assignments\Type;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignmentSimpleText
 *
 * @property int $id
 * @property int $minute_id
 * @property string $label
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText whereMinuteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentSimpleText whereValue($value)
 * @mixin \Eloquent
 */
class AssignmentSimpleText extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'value',
        'minute_id',
    ];

    /** @var mixed[] */
    protected $appends = ['type'];

    public $timestamps = false;

    /** @return Attribute<string, never> */
    public function type(): Attribute
    {
        return new Attribute(get: fn () => MeetItem::SIMPLE_TEXT_TYPE);
    }
}
