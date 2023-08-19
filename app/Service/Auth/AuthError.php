<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Error\Error;

enum AuthError implements Error
{
    case USER_NOT_FOUND;
    case WRONG_PASSWORD;

    public function message(): string
    {
        return match ($this) {
            self::USER_NOT_FOUND, self::WRONG_PASSWORD => 'login error, user or password is wrong'
        };
    }
}
