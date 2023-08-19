<?php

declare(strict_types=1);

namespace App\Service\Meet;

use App\Models\MeetType;

readonly class AddMeetType
{
    use NameFormat;

    public function __construct(private MeetType $meetType)
    {
    }

    public function addNew(string $name): MeetType
    {
        return $this->meetType->create([
            'name' => $this->format($name),
        ]);
    }
}
