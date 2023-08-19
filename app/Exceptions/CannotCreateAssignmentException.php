<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Response\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CannotCreateAssignmentException extends Exception
{
    public function __construct(string $msg)
    {
        $this->message = $msg;
    }

    public function render(Request $request)
    {
        if ($request->is('api/*')) {
            return ApiResponse::bad($this->getMessage());
        }
    }

    public function report(): void
    {
        Log::error($this->getMessage());
    }
}
