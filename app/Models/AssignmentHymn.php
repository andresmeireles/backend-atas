<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\Minute\Assignments\Type;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignmentHymn
 *
 * @property int $id
 * @property int $minute_id
 * @property string $label
 * @property string $name
 * @property int $number
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn whereMinuteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentHymn whereNumber($value)
 * @mixin \Eloquent
 */
class AssignmentHymn extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'name',
        'number',
        'minute_id',
    ];

    /** @var mixed[] */
    protected $appends = ['type'];

    public $timestamps = false;

    /** @return Attribute<string, never> */
    public function type(): Attribute
    {
        return new Attribute(get: fn () => MeetItem::HYMN_TYPE);
    }
}
