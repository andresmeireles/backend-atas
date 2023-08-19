<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\MinuteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', fn () => response()->json(Auth::user()));

        Route::prefix('meets')->group(function () {
            Route::get('/', [MeetController::class, 'getAll']);
            Route::get('/{name}', [MeetController::class, 'getById'])->name('meet.get');
            Route::get('/{id}/items', [MeetController::class, 'getItemsByType'])->name('meet.items');
        });

        Route::prefix('items')->group(function () {
            Route::get('/{id}', [MeetController::class, 'getItemById'])->name('items.get');
        });

        Route::prefix('minutes')->group(function () {
            Route::get('/', [MinuteController::class, 'all']);
            Route::get('/{id}', [MinuteController::class, 'get']);

            Route::post('/', [MinuteController::class, 'add'])->name('minutes.add');

            Route::put('/{id}', [MinuteController::class, 'update'])
                ->whereNumber('id')
                ->name('minutes.update');
        });
    });
});
