<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthUser
{
    /**
     * Get user or throw exceptoin if is null
     *
     * @throws \LogicException
     */
    public static function user(): User&Authenticatable
    {
        $user = Auth::user();
        if ($user === null) {
            throw new \LogicException('User cannot be null on this part of code');
        }

        return $user;
    }
}
