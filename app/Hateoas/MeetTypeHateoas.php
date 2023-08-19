<?php

declare(strict_types=1);

namespace App\Hateoas;

use App\Models\MeetType;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class MeetTypeHateoas
{
    use CreatesLinks;

    public function self(MeetType $meetType): ?Link
    {
        return $this->link('meet.get', ['name' => $meetType->name]);
    }

    public function items(MeetType $meetType): ?Link
    {
        return $this->link('meet.items', ['id' => $meetType->id]);
    }
}
