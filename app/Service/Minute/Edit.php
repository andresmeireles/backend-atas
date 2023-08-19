<?php

declare(strict_types=1);

namespace App\Service\Minute;

use App\Error\AppError;
use App\Error\Error;
use App\Models\Minute;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class Edit
{
    public function __construct(
        private readonly Minute $minute,
        private readonly Create $create,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateById(int $id, array $data, User $user): Minute|Error
    {
        $minute = $this->minute->find($id);
        if ($minute === null) {
            return AppError::REGISTER_NOT_EXISTS;
        }
        DB::beginTransaction();
        try {
            $data['code'] = $minute->code;
            $data['schema'] = $minute->schema;
            $data['date'] = $minute->date;
            $updatedMinute = $this->create->create($user, $data);
            if ($updatedMinute instanceof Error) {
                return $updatedMinute;
            }
            $activeMinute = $minute->activeMinute;
            if ($activeMinute === null) {
                // TODO: alterar erro para um que faca mais sentido
                throw new InvalidArgumentException('minute cannot be updated');
            }
            if ($minute->code !== $updatedMinute->code) {
                // TODO: alterar erro para um que faca mais sentido
                throw new RuntimeException('uuid\'s must be the same');
            }
            $activeMinute->minute_id = $updatedMinute->id;
            $activeMinute->save();
            DB::commit();
            return $minute;
        } catch (QueryException $err) {
            DB::rollBack();
            dump($err->getMessage());
            Log::error($err->getMessage());
            return AppError::DB_ERROR;
        } catch (InvalidArgumentException $err) {
            db::rollback();
            log::error($err->getMessage());
            return MinuteError::CANNOT_UPDATE_NON_EXISTING_ACTIVE_MINUTE;
        } catch (RuntimeException $err) {
            db::rollback();
            log::error($err->getMessage());
            return MinuteError::CANNOT_UPDATE_NON_EXISTING_ACTIVE_MINUTE;
        }
    }
}
