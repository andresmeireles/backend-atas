<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Auth;

use App\Models\User;
use App\Service\Auth\AuthError;
use App\Service\Auth\GenerateToken;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class GenerateTokenTest extends TestCase
{
    use RefreshDatabase;

    private GenerateToken $service;

    private LoggerInterface&MockObject $logger;

    private Hasher&MockObject $hash;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->hash = $this->createMock(Hasher::class);
        $this->service = new GenerateToken(new User(), $this->logger, $this->hash);
    }

    public function testGenerate(): void
    {
        // arrange
        $user = User::factory()->create();
        $this->hash->expects($this->once())->method('check')->willReturn(true);

        // act
        $result = $this->service->generateToken($user->username, $user->password);

        // assert
        self::assertIsString($result);
    }

    public function testGenerateWrongUser(): void
    {
        // arrange
        $user = User::factory()->create();
        $this->hash->expects($this->never())->method('check')->willReturn(false);
        $this->logger->expects($this->once())->method('error');

        // act
        $result = $this->service->generateToken('wrong user', $user->password);

        // assert
        self::assertSame($result, AuthError::USER_NOT_FOUND);
    }

    public function testGenerateWrongPassword(): void
    {
        // arrange
        $user = User::factory()->create();
        $this->hash->expects($this->once())->method('check')->willReturn(false);
        $this->logger->expects($this->once())->method('error');

        // act
        $result = $this->service->generateToken($user->username, '');

        // assert
        self::assertSame($result, AuthError::WRONG_PASSWORD);
    }
}
