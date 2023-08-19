<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Error\Error;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Response\ApiResponse;
use App\Service\Auth\GenerateToken;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request, GenerateToken $generateToken): JsonResponse
    {
        $token = $generateToken->generateToken($request->username, $request->password);
        if ($token instanceof Error) {
            return ApiResponse::bad($token->message());
        }
        return ApiResponse::ok(['token' => $token]);
    }
}
