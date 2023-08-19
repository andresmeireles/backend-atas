<?php

namespace App\Exceptions;

use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->renderable(function (ValidationException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return ApiResponse::bad('wrong or missing parameters');
        //     }
        // });
        // $this->renderable(function (NotFoundHttpException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return ApiResponse::notFound($e->getMessage());
        //     }
        // });
        // if (!app()->isLocal()) {
        //     $this->renderable(function (Throwable $e, Request $request) {
        //         if ($request->is('api/*')) {
        //             return ApiResponse::serverError('internal server error, try again latter');
        //         }
        //     });
        // }
        $this->reportable(function (NotFoundHttpException $e) {
            Log::alert($e->getMessage());
        });
        $this->reportable(function (Throwable $e) {
            Log::alert($e->getMessage());
        });
    }
}
