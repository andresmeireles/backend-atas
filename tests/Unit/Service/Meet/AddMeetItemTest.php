<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Meet;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\MeetItem;
use App\Service\Meet\AddMeetItem;

// TODO: test repeated name
class AddMeetItemTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAddNewMeetItem(): void
    {
        $addData = [
            'name' => 'New_Meeting',
            'type' => 'call'
        ];
        $data = array_map('strtolower', $addData);

        $meetItem = new MeetItem();
        $addMeetItem = new AddMeetItem($meetItem);

        $result = $addMeetItem->addNew($data);

        $this->assertDatabaseHas('meet_items', [
            'name' => $data['name'],
            'type' => $data['type']
        ]);
        $this->assertEquals($data['name'], $result->name);
        $this->assertEquals($data['type'], $result->type);
    }
}
