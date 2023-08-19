<?php

namespace App\Hateoas;

use App\Models\MeetItem;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class MeetItemHateoas
{
    use CreatesLinks;

    public function self(MeetItem $meetItem): ?Link
    {
        return $this->link('items.get', ['id' => $meetItem->id]);
    }
}
