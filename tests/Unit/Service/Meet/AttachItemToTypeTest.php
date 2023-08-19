<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Meet;

use App\Models\MeetItem;
use App\Models\MeetType;
use App\Service\Meet\AttachItemToType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachItemToTypeTest extends TestCase
{
    use RefreshDatabase;

    public function testAttachItem(): void
    {
        $meetType = MeetType::factory()->create();
        $item = MeetItem::factory()->create();

        $attachItemToType = new AttachItemToType(new MeetType());
        $attachItemToType->attachItem($meetType->id, $item);

        self::assertSame(1, $meetType->items->count());
    }

    public function testAttachItemThrowsExceptionIfTypeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Meet type not found');

        $meetType = MeetType::factory()->create();
        $item = MeetItem::factory()->create();

        $attachItemToType = new AttachItemToType($meetType);
        $attachItemToType->attachItem(999, $item);
    }
}
