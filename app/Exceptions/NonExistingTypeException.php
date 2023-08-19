<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Response\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NonExistingTypeException extends Exception
{
    public function __construct(string $type)
    {
        $this->message = sprintf('Type %s not exists', $type);
    }

    public function render(Request $request): ?JsonResponse
    {
        if ($request->is('api/*')) {
            return ApiResponse::bad($this->getMessage());
        }
        return null;
    }
}
