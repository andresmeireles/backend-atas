<?php

declare(strict_types=1);

namespace App\Error;

enum AppError implements Error
{
    case UNDEFINED_ERROR;
    case DB_ERROR;
    case REGISTER_NOT_EXISTS;

    public function message(): string
    {
        return match ($this) {
            self::UNDEFINED_ERROR => 'erro indefinido',
            self::DB_ERROR => 'erro no banco de dados',
            self::REGISTER_NOT_EXISTS => 'register not founded or not exists'
        };
    }
}
