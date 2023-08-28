<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\AuthUser;
use App\Error\Error;
use App\Http\Requests\AddMinuteRequest;
use App\Http\Requests\EditMinuteRequest;
use App\Http\Response\ApiResponse;
use App\Models\ActiveMinute;
use App\Models\Minute;
use App\Service\Minute\Add;
use App\Service\Minute\Edit;
use Illuminate\Http\JsonResponse;

class MinuteController extends Controller
{
    public function add(AddMinuteRequest $request, Add $add): JsonResponse
    {
        $addedMinute = $add->addNewMinute($request->validated(), AuthUser::user());
        if ($addedMinute instanceof Error) {
            return ApiResponse::bad($addedMinute->message());
        }
        return ApiResponse::ok($addedMinute);
    }

    public function update(int $id, EditMinuteRequest $request, Edit $edit): JsonResponse
    {
        $editedMinute = $edit->updateById($id, $request->validated(), AuthUser::user());
        if ($editedMinute instanceof Error) {
            return ApiResponse::bad($editedMinute->message());
        }
        return ApiResponse::ok($editedMinute);
    }

    public function get(int $id, Minute $minute): JsonResponse
    {
        return ApiResponse::ok($minute->find($id));
    }

    public function all(ActiveMinute $activeMinute): JsonResponse
    {
        $activeMinutes = $activeMinute->all();
        $minutes = $activeMinutes->map(fn ($model) => $model->minute);
        return ApiResponse::ok($minutes);
    }
}
