<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Minute;

use App\Models\ActiveMinute;
use App\Models\Minute;
use App\Models\User;
use App\Service\Minute\Create;
use App\Service\Minute\Edit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * TODO: teste de COMPLETE
 */
class EditTest extends TestCase
{
    use RefreshDatabase;

    private string $assign = '
        [
            {
    "type": "simple_text",
    "label": "first_pray",
    "value": "bispo"
    },
    {
    "type": "simple_text",
    "label": "ending_pray",
    "value": "bispo"
    },
    {
    "type": "simple_text",
    "label": "first_speaker",
    "value": "bispo"
    },    {
    "type": "simple_text",
    "label": "second_speaker",
    "value": "bispo"
    },
{
    "type": "hym",
    "label": "first_hym",
    "name": "hino",
    "number": 22
            },
{
    "type": "hym",
    "label": "sacramental_hym",
    "name": "hino",
    "number": 22
            },
{
    "type": "hym",
    "label": "intermediary_hym",
    "name": "hino",
    "number": 22
            },
{
    "type": "hym",
    "label": "ending_hym",
    "name": "hino",
    "number": 22
            },
{
    "type": "call",
    "label": "presiding",
    "name": "hino",
    "call": "bispo"
            },
{
    "type": "call",
    "label": "driving",
    "name": "andre",
    "call": "bispo"
            }
        ]
    ';

    private Edit $edit;

    private Create&MockObject $create;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create = $this->createMock(Create::class);
        $this->edit = new Edit(new Minute(), $this->create);
    }

    public function testCreate(): void
    {
        // arrange
        $user = User::factory()->create();
        $minute = Minute::factory()->create([
            'date' => now()->toDate()->format('Y-m-d'),
            'user_id' => $user->id,
            'schema' => 1
        ]);
        ActiveMinute::factory()->create([
            'minute_id' => $minute->id
        ]);
        $this->create->expects($this->once())->method('create')->willReturn($minute);

        // act
        $result = $this->edit->updateById(
            $minute->id,
            [
                'schema' => 1,
                'date' => '2023-06-09',
                'assignments' => $this->assign
            ],
            $user
        );

        // assert
        self::assertInstanceOf(Minute::class, $result);
    }
}
