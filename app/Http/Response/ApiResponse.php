<?php

declare(strict_types=1);

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    private function __construct()
    {
    }

    public static function ok(mixed $data): JsonResponse
    {
        return response()->json($data)->setStatusCode(200);
    }

    public static function bad(string $message): JsonResponse
    {
        return self::simpleMessageReturn($message, 400);
    }

    public static function notFound(string $message): JsonResponse
    {
        return self::simpleMessageReturn($message, 404);
    }

    public static function serverError(string $message): JsonResponse
    {
        return self::simpleMessageReturn($message, 500);
    }

    private static function simpleMessageReturn(string $message, int $httpCode): JsonResponse
    {
        return response()->json(['message' => $message], $httpCode);
    }
}
