<?php

namespace App\Models;

use App\Service\Minute\Meeting\MeetStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\MeetType
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MeetItem> $items
 * @property-read int|null $items_count
 * @method static \Database\Factories\MeetTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MeetType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetType whereName($value)
 * @mixin \Eloquent
 */
class MeetType extends Model
{
    use HasFactory;

    public const SACRAMENTAL = 'sacramental';
    public const SACRAMENTAL_TESTIMONY = 'sacramental_testimony';

    public const ITEM_TYPES = [
        self::SACRAMENTAL, self::SACRAMENTAL_TESTIMONY
    ];

    protected $fillable = [
        'name',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'meet_items',
    ];

    public $timestamps = false;

    /** @return Attribute<MeetItem, never> */
    public function meetItems(): Attribute
    {
        return new Attribute(
            get: fn () => $this->items()->get()
        );
    }

    /**
     * @return BelongsToMany<MeetItem>
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(MeetItem::class, 'meet_type_items')->withPivot('is_obligatory', 'is_repeatable');
    }

    public function findByName(string $name): ?MeetType
    {
        return $this->where('name', $name)->first();
    }

    /** @param string[] $items */
    public function status(string $schemaName, array $items): MeetStatus
    {
        $type = $this->findByName($schemaName);
        if ($type === null) {
            // TODO: alterar para uma exception melhor
            throw new \Exception('not found');
        }

        $isComplete = true;
        /** @var string[] */
        $obligatory = $type
            ->items
            ->where(fn (MeetItem $i) => $i->pivot?->is_obligatory ?? true)
            ->map(fn (MeetItem $i) => $i->name)
            ->toArray();

        /** @var string[] */
        $nonObligatory = $type
            ->items
            ->map(fn (MeetItem $i) => $i->name)
            ->filter(fn (string $a) => !in_array($a, $obligatory))
            ->toArray();

        foreach ($obligatory as $o) {
            if (!in_array($o, $items)) {
                return MeetStatus::DRAFT;
            }
        }

        foreach ($nonObligatory as $no) {
            if (!in_array($no, $items)) {
                $isComplete = false;
                break;
            }
        }

        return $isComplete ? MeetStatus::COMPLETE : MeetStatus::HAS_OBLIGATORY;
    }
}
