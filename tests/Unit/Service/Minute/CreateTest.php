<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Minute;

use App\Models\AssignmentCall;
use App\Models\AssignmentHymn;
use App\Models\AssignmentSimpleText;
use App\Models\MeetItem;
use App\Models\MeetType;
use App\Models\Minute;
use App\Models\User;
use App\Service\Minute\Create;
use App\Service\Minute\CreateAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * TODO: teste de COMPLETE
 */
class CreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<array<string, mixed>>
     */
    private array $assign = [
        [
            "type" => "simple_text",
            "label" => "first_pray",
            "value" => "bispo"
        ],
        [
            "type" => "simple_text",
            "label" => "ending_pray",
            "value" => "bispo"
        ],
        [
            "type" => "simple_text",
            "label" => "first_speaker",
            "value" => "bispo"
        ],
        [
            "type" => "simple_text",
            "label" => "third_speaker",
            "value" => "bispo"
        ],
        [
            "type" => "hym",
            "label" => "first_hym",
            "name" => "hino",
            "number" => 22
        ],
        [
            "type" => "hym",
            "label" => "sacramental_hym",
            "name" => "hino",
            "number" => 22
        ],
        [
            "type" => "hym",
            "label" => "intermediary_hym",
            "name" => "hino",
            "number" => 22
        ],
        [
            "type" => "hym",
            "label" => "ending_hym",
            "name" => "hino",
            "number" => 22
        ],
        [
            "type" => "call",
            "label" => "presiding",
            "name" => "hino",
            "call" => "bispo"
        ],
        [
            "type" => "call",
            "label" => "driving",
            "name" => "andre",
            "call" => "bispo"
        ],
        [
            "type" => "hym",
            "label" => "intermediary_hym",
            "name" => "andre",
            "number" => 22
        ]
    ];

    private Create $create;

    protected function setUp(): void
    {
        parent::setUp();

        $meet = MeetType::factory()->create(['name' => 'sacramental']);
        $item = MeetItem::factory()->create(['name' => 'presiding', 'type' => 'call']);
        $meet->items()->attach($item, ['is_obligatory' => true, 'is_repeatable' => false]);

        $this->create = new Create(
            minute: new Minute(),
            meetType: new MeetType(),
            createAssignment: new CreateAssignment(
                new AssignmentCall(),
                new AssignmentHymn(),
                new AssignmentSimpleText()
            )
        );
    }

    public function testCreate(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $result = $this->create->create($user, [
            'schema' => 'sacramental',
            'date' => now()->format('Y-m-d'),
            'assignments' => $this->assign,
        ]);

        // assert
        self::assertIsNumeric($result->id);
    }

    public function testCreateDraft(): void
    {
        // arrange
        $a = $this->assign;
        unset($a[0]);
        $user = User::factory()->create();

        // act
        $result = $this->create->create($user, [
            'schema' => 'sacramental',
            'date' => '2023-06-09',
            'assignments' => $a
        ]);

        // assert
        self::assertIsNumeric($result->id);
    }
}
