<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Minute;

use App\Models\ActiveMinute;
use App\Models\AssignmentCall;
use App\Models\AssignmentHymn;
use App\Models\AssignmentSimpleText;
use App\Models\Minute;
use App\Models\User;
use App\Service\Minute\Add;
use App\Service\Minute\Create;
use App\Service\Minute\MinuteError;
use App\Service\Minute\CreateAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * TODO: teste de COMPLETE
 */
class AddTest extends TestCase
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

    private Add $add;

    private Create&MockObject $create;

    private ActiveMinute&MockObject $activeMinute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create = $this->createMock(Create::class);
        $this->activeMinute = $this->createMock(ActiveMinute::class);
        $this->add = new Add(
            new Minute(),
            $this->activeMinute,
            $this->create
        );
    }

    public function testCreate(): void
    {
        // arrange
        $user = User::factory()->create();
        $this->create->expects($this->once())->method('create')->willReturn(
            Minute::factory()->make([
                'date' => now()->format('Y-m-d'),
                'user_id' => $user->id,
                'schema' => "feijão",
                'id' => 1
            ])
        );

        // act
        $result = $this->add->addNewMinute([
            'schema' => 1,
            'date' => now()->format('Y-m-d'),
            'assignments' => $this->assign
        ], $user);

        // assert
        self::assertInstanceOf(Minute::class, $result);
    }



    public function testeCreateWithSameDate(): void
    {
        // arrange
        $user = User::factory()->create();
        $this->create->expects($this->any())->method('create')->willReturn(Minute::factory()->create([
            'date' => now()->toDate()->format('Y-m-d'),
            'user_id' => $user->id,
            'schema' => 1
        ]));

        // act
        $this->add->addNewMinute([
            'schema' => 1,
            'date' => now()->toDate()->format('Y-m-d'),
            'assignments' => $this->assign
        ], $user);
        $result = $this->add->addNewMinute([
            'schema' => 1,
            'date' => now()->toDate()->format('Y-m-d'),
            'assignments' => $this->assign
        ], $user);

        // assert
        self::assertSame($result, MinuteError::MINUTE_ALREADY_EXISTS);
    }
}
