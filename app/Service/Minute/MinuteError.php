<?php

declare(strict_types=1);

namespace App\Service\Minute;

use App\Error\Error;

enum MinuteError implements Error
{
    case MINUTE_ALREADY_EXISTS;
    case INVALID;
    case CANNOT_BE_LESSER_THAN_TODAY;
    case CANNOT_UPDATE_NON_EXISTING_ACTIVE_MINUTE;
    case MINUTES_MUST_HAVE_SAME_CODE;
    case MEET_TYPE_NOT_EXISTS;
    case INVALID_ASSIGNMENTS;

    public function message(): string
    {
        return match ($this) {
            self::MINUTE_ALREADY_EXISTS => 'minute exists',
            self::INVALID => 'minute not has obligatory assignments',
            self::CANNOT_BE_LESSER_THAN_TODAY => 'minute date must not be less than current date',
            self::CANNOT_UPDATE_NON_EXISTING_ACTIVE_MINUTE => 'cannot update non existing active minute',
            self::MINUTES_MUST_HAVE_SAME_CODE => 'updated minutes must have same code',
            self::MEET_TYPE_NOT_EXISTS => 'meet type not exists',
            self::INVALID_ASSIGNMENTS => 'invalid assignments',
        };
    }
}
