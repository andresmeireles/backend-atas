<?php

declare(strict_types=1);

namespace App\Service\Meet;

use App\Models\MeetItem;

readonly class AddMeetItem
{
    use NameFormat;

    public function __construct(private readonly MeetItem $meetItem)
    {
    }

    /**
     * @param array{name: string, type: string} $data
     */
    public function addNew(array $data): MeetItem
    {
        $newData = array_map($this->format(...), $data);
        return $this->meetItem->create($newData);
    }
}
