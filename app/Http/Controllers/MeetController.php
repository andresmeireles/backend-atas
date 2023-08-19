<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\MeetItemResource;
use App\Http\Resources\MeetTypeResource;
use App\Http\Response\ApiResponse;
use App\Models\MeetItem;
use App\Models\MeetType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeetController extends Controller
{
    public function getById(string $name, MeetType $meetType): JsonResponse
    {
        $meet = $meetType->findByName($name);
        if ($meet === null) {
            return ApiResponse::bad('meet not founded');
        }

        return ApiResponse::ok(new MeetTypeResource($meet));
    }

    public function getAll(MeetType $meetType): JsonResponse
    {
        $meet = $meetType->all();
        return ApiResponse::ok(MeetTypeResource::collection($meet));
    }

    public function getItemsByType(int $meetTypeId, MeetType $meetType): JsonResponse
    {
        $meet = $meetType->find($meetTypeId);
        if ($meet === null) {
            return ApiResponse::bad('meet not founded');
        }

        return ApiResponse::ok(MeetItemResource::collection($meet->items));
    }

    public function getItemById(int $id, MeetItem $meetType): JsonResponse
    {
        $item = $meetType->find($id);
        if ($item === null) {
            return ApiResponse::bad('meet not founded');
        }

        return ApiResponse::ok(new MeetItemResource($item));
    }
}
