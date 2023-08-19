<?php

declare(strict_types=1);

namespace App\Service\Minute;

use App\Error\Error;
use App\Models\MeetItem;
use App\Models\MeetType;
use App\Models\Minute;
use App\Models\User;
use App\Service\Minute\Assignments\Label;
use App\Service\Minute\Meeting\MeetStatus;
use App\Service\Minute\Schemas\Definitions\Schema;
use App\Service\Minute\Schemas\Definitions\SchemaTypes;
use App\Service\Minute\Schemas\Sacramental;
use Illuminate\Log\Logger;
use Validator;

class Create
{
    public function __construct(
        private readonly Minute $minute,
        private readonly MeetType $meetType,
        private readonly CreateAssignment $createAssignment
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(User $user, array $data): Minute|Error
    {
        $schema = $this->getSchema($data['schema']);
        if ($schema === null) {
            return MinuteError::MEET_TYPE_NOT_EXISTS;
        }

        if (!$this->validatedAssignments($data['assignments'])) {
            return MinuteError::INVALID_ASSIGNMENTS;
        }

        $assignments = $this->filterAssignmentsByMeetItems($schema, $data['assignments']);
        if ($assignments === []) {
            return MinuteError::INVALID_ASSIGNMENTS;
        }

        $data['user_id'] = $user->id;
        $data['code'] = $data['code'] ?? $this->createCode($data);
        $data['meet_type_id'] = $schema->id;
        $data['status'] = $this->meetType->status(
            schemaName: $data['schema'],
            items: array_map(fn (array $a): string => $a['label'], $assignments)
        );
        $minute = $this->minute->create($data);
        array_map(
            fn (array $a) => $this->createAssignment->createAssignment($minute, $a),
            $assignments,
        );

        return $minute;
    }

    /**
     * @param mixed[] $assignments
     *
     * @return mixed[]
     */
    private function filterAssignmentsByMeetItems(MeetType $meetType, array $assignments): array
    {
        $filteredItems = [];
        $items = $meetType->items;

        foreach ($assignments as $a) {
            $label = strtolower($a['label']);
            $type = $a['type'];
            $item = $items->first(fn (MeetItem $i) => $i->name === $label && $i->type === $type);

            if ($item === null) {
                continue;
            }

            if ($item->pivot->is_repeatable) {
                $filteredItems[] = $a;
                continue;
            }
            if (!in_array(fn (array $f) => $f['label'] === $label, $filteredItems)) {
                $filteredItems[] = $a;
            }
        }

        return $filteredItems;
    }

    private function getSchema(string $schema): ?MeetType
    {
        return $this->meetType->where('name', trim($schema))->first();
    }

    /** @param array<string, mixed> $data */
    private function createCode(array $data): string
    {
        return sprintf('S-%s-%s', $data['schema'], now()->getTimestamp());
    }

    private function validatedAssignments(array $assignments): bool
    {
        if (count($assignments) === 0) {
            return false;
        }

        foreach ($assignments as $a) {
            $validate = Validator::make($a, [
                'label' => 'required',
                'type' => 'required',
            ]);

            if ($validate->fails()) {
                return false;
            }
        }

        return true;
    }
}
