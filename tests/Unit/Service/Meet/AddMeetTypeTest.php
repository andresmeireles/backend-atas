<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Meet;

use App\Models\MeetType;
use App\Service\Meet\AddMeetType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddMeetTypeTest extends TestCase
{
    use RefreshDatabase;

    public function testAddNew(): void
    {
        $addMeetType = new AddMeetType(new MeetType());
        $result = $addMeetType->addNew('Test Name');

        $this->assertInstanceOf(MeetType::class, $result);
        $this->assertEquals('test_name', $result->name);
    }
}
