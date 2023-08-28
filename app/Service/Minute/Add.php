<?php

declare(strict_types=1);

namespace App\Service\Minute;

use App\Error\AppError;
use App\Error\Error;
use App\Models\ActiveMinute;
use App\Models\Minute;
use App\Models\User;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class Add
{
    public function __construct(
        private Minute $minute,
        private ActiveMinute $activeMinute,
        private Create $create
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function addNewMinute(array $data, User $user): Minute|Error
    {
        $dt = DateTime::createFromFormat('Y-m-d', $data['date']);
        if ($dt === false) {
            return AppError::UNDEFINED_ERROR;
        }
        $dt->setTime(23, 59, 59);
        if ($dt->getTimestamp() < now()->getTimestamp()) {
            return MinuteError::CANNOT_BE_LESSER_THAN_TODAY;
        }
        if ($this->exists($data['date'])) {
            return MinuteError::MINUTE_ALREADY_EXISTS;
        }
        DB::beginTransaction();
        try {
            $minute = $this->create->create($user, $data);
            if ($minute instanceof Error) {
                DB::rollBack();
                return $minute;
            }
            $this->activeMinute->create([
                'minute_id' => $minute->id
            ]);
            DB::commit();
            return $minute;
        } catch (QueryException $err) {
            DB::rollBack();
            Log::error($err->getMessage());
            return AppError::DB_ERROR;
        }
    }

    private function exists(string $date): bool
    {
        return $this->minute->where('date', $date)->first() !== null;
    }
}
