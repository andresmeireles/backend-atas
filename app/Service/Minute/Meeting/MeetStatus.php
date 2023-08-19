<?php

declare(strict_types=1);

namespace App\Service\Minute\Meeting;

enum MeetStatus: string
{
    case COMPLETE = 'complete';
    case HAS_OBLIGATORY = 'has_obligatory';
    case DRAFT = 'draft';
    case EXPIRED = 'expired';
}
