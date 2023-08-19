<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ActiveMinute;
use App\Models\MeetItem;
use App\Models\MeetType;
use App\Models\Minute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MinuteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $meet = MeetType::factory()->create([
            'name' => 'sacramental'
        ]);
        $item = MeetItem::factory()->create([
            'name' => 'presiding',
            'type' => 'call'
        ]);
        $meet->items()->attach($item, ['is_obligatory' => true, 'is_repeatable' => false]);
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // arrange
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // act
        $response = $this
            ->post(
                '/api/v1/minutes',
                [
                    'date' => '2025-04-04',
                    'schema' => 'sacramental',
                    'assignments' => [
                        [
                            "type" => "call",
                            "name" => "Andre",
                            "label" => "presiding",
                            "call" => "bixpo"
                        ],
                    ]
                ]
            );
        // dd($response->json());
        // assert
        $response->assertStatus(200);
    }

    public function testNonExistingAssignment(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this
            ->post(
                '/api/v1/minutes',
                [
                    'date' => '2025-04-04',
                    'schema' => 'sacramental',
                    'assignments' => [
                        [
                            "type" => "callen",
                            "name" => "Andre",
                            "label" => "presiding",
                            "call" => "bixpo"
                        ],
                    ]
                ]
            );
        // dd($response->json());

        //assert
        $response->assertStatus(400);
    }

    public function test_fail(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post(
            '/api/v1/minutes/',
            [
                'date' => now()->subDay()->format('Y-m-d'),
                'schema' => 'bishop_meeting',
                'assignments' => [
                    [
                        "type" => "call",
                        "name" => "Andre",
                        "label" => "presiding",
                        "call" => "bixpo"
                    ],
                ]
            ]
        );

        $response->assertStatus(400);
    }

    public function test_update(): void
    {
        // arrange
        $user =  User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $minute = Minute::factory()->create([
            'schema' => 'sacramental',
            'meet_type_id' => 1,
            'date' => now()->format('Y-m-d'),
            'user_id' => $user->id
        ]);
        ActiveMinute::factory()->create([
            'minute_id' => $minute->id
        ]);

        // act
        $response = $this->put(
            '/api/v1/minutes/' . $minute->id,
            [
                'assignments' => [
                    [
                        "type" => "call",
                        "name" => "Andre",
                        "label" => "presiding",
                        "call" => "bixpo"
                    ],
                ],
            ]
        );
        // dd($response->json());

        // assert
        $response->assertOk();
    }

    public function test_all(): void
    {
        // arrange
        $user =  User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $minute = Minute::factory()->create([
            'schema' => 'sacramental',
            'date' => now()->format('Y-m-d'),
            'user_id' => $user->id
        ]);
        ActiveMinute::factory()->create([
            'minute_id' => $minute->id
        ]);

        // act
        $response = $this->get('api/v1/minutes/');

        // assert
        $response->assertOk();
        $response->assertJsonIsArray();
    }

    public function test_get(): void
    {
        // arrange
        $user =  User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $minute = Minute::factory()->create([
            'schema' => 'sacramental',
            'date' => now()->format('Y-m-d'),
            'user_id' => $user->id
        ]);
        ActiveMinute::factory()->create([
            'minute_id' => $minute->id
        ]);

        // act
        $response = $this->get('api/v1/minutes/1');

        // assert
        $response->assertOk();
        $response->assertJsonIsObject();
    }
}
