<?php

declare(strict_types=1);

namespace App\Service\Minute;

use App\Error\Error;
use App\Models\AssignmentCall;
use App\Models\AssignmentHymn;
use App\Models\AssignmentSimpleText;
use App\Models\MeetItem;
use App\Models\Minute;
use Illuminate\Log\Logger;

class CreateAssignment
{
    public function __construct(
        private readonly AssignmentCall $assignmentCall,
        private readonly AssignmentHymn $assignmentHymn,
        private readonly AssignmentSimpleText $assignmentSimple,
    ) {
    }

    public function createAssignment(
        Minute $minute,
        array $assignment
    ): AssignmentCall|AssignmentHymn|AssignmentSimpleText|Error {
        $type = (string) $assignment['type'];
        $label = $assignment['label'];
        return match ($type) {
            MeetItem::CALL_TYPE => $this->assignmentCall->create([
                'label' => $label,
                'minute_id' => $minute->id,
                'name' => $assignment['name'],
                'call' => $assignment['call']
            ]),
            MeetItem::HYMN_TYPE =>  $this->assignmentHymn->create([
                'label' => $label,
                'minute_id' => $minute->id,
                'name' => $assignment['name'],
                'number' => $assignment['number']
            ]),
            MeetItem::SIMPLE_TEXT_TYPE => $this->addSimpleText($label, $minute->id, $assignment['value']),
            default => MinuteError::INVALID_ASSIGNMENTS
        };
    }

    private function addSimpleText(string $label, int $minuteId, string $value): AssignmentSimpleText
    {
        Logger(sprintf("%s - %s - %s", $label, $minuteId, $value));
        return $this->assignmentSimple->create([
            'label' => strtoupper($label),
            'minute_id' => $minuteId,
            'value' => $value
        ]);
    }
}
