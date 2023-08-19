<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ChangeStatusOfExpireMinuteCommand;
use App\Models\ActiveMinute;
use App\Models\Minute;
use App\Models\User;
use App\Service\Minute\Meeting\MeetStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeStatusOfExpireMinuteCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle(): void
    {
        // Arrange
        ActiveMinute::factory()->create([
            'minute_id' => Minute::factory()->create([
                'date' => now()->subDay(),
                'user_id' => User::factory()->create()->id,
                'schema' => 'sacramental'
            ])->id,
        ]);

        $sut = new ChangeStatusOfExpireMinuteCommand(new ActiveMinute());

        // Act
        $sut->handle();

        // Assert
        self::assertSame(1, Minute::where('status', MeetStatus::EXPIRED->value)->count());
    }

    public function testHandleToday(): void
    {
        // Arrange
        ActiveMinute::factory()->create([
            'minute_id' => Minute::factory()->create([
                'date' => now(),
                'user_id' => User::factory()->create()->id,
                'schema' => 'sacramental'
            ])->id,
        ]);

        $sut = new ChangeStatusOfExpireMinuteCommand(new ActiveMinute());

        // Act
        $sut->handle();

        // Assert
        self::assertSame(0, Minute::where('status', MeetStatus::EXPIRED->value)->count());
    }
}
