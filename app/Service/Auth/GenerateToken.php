<?php

namespace App\Service\Auth;

use App\Error\Error;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Psr\Log\LoggerInterface;

readonly class GenerateToken
{
    public function __construct(private User $user, private LoggerInterface $logger, private Hasher $hash)
    {
    }

    public function generateToken(string $username, string $password): string|Error
    {
        /** @var User|null */
        $user = $this->user->where('username', $username)->first();
        if ($user === null) {
            $this->logger->error(sprintf('User %s not exist', $username));
            return AuthError::USER_NOT_FOUND;
        }
        $validPassword = $this->hash->check($password, $user->password);
        if (!$validPassword) {
            $this->logger->error(sprintf('User %s enter with wrong password', $username));
            return AuthError::WRONG_PASSWORD;
        }
        return $user->createToken(sprintf('%s token', $username))->plainTextToken;
    }
}
