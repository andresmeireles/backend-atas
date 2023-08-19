<?php

declare(strict_types=1);

namespace App\Service\Meet;

use App\Models\MeetItem;
use App\Models\MeetType;

readonly class AttachItemToType
{
    public function __construct(private MeetType $meetType)
    {
    }

    public function attachItem(int $id, MeetItem $item, bool $repeatable = false, bool $obligatory = false): void
    {
        $type = $this->meetType->find($id);
        if ($type === null) {
            throw new \InvalidArgumentException('Meet type not found');
        }
        $type->items()->attach($item->id, [
            'is_repeatable' => $repeatable,
            'is_obligatory' => $obligatory
        ]);
    }
}
