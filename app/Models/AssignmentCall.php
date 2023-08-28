<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\Minute\Assignments\Type;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignmentCall
 *
 * @property int $id
 * @property int $minute_id
 * @property string $label
 * @property string $name
 * @property string $call
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall whereCall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall whereMinuteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentCall whereName($value)
 * @mixin \Eloquent
 */
class AssignmentCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'name',
        'call',
        'minute_id',
    ];

    /** @var mixed[] */
    protected $appends = ['type'];

    public $timestamps = false;

    /**
     * @return Attribute<string, never>
     */
    public function type(): Attribute
    {
        return new Attribute(get: fn () => MeetItem::CALL_TYPE);
    }
}
