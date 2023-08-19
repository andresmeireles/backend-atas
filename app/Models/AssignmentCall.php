<?php

declare(strict_types=1);

namespace App\Models;

use App\Service\Minute\Assignments\Type;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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