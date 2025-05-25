<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountDepositController;
use App\Http\Controllers\AccountWithdrawalController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class);

Route::prefix('auth')->group( function () {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::middleware('auth:sanctum')->group( function () {
        Route::get('user', [AuthenticationController::class, 'user']);
        Route::get('logout', [AuthenticationController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('set/pin', [PinController::class, 'setPin']);
    Route::middleware('has.set.pin')->group( function () {
        Route::post('validate/pin', [PinController::class, 'validatePin']);
        Route::post('account', [AccountController::class, 'store']);
    });

    Route::middleware('has.set.pin')->group( function () {
        Route::prefix('account')->group( function () {
            Route::post('deposit', [AccountDepositController::class, 'store']);
            Route::post('withdrawal', [AccountWithdrawalController::class, 'store']);
            Route::post('transfer', [TransferController::class, 'store']);
        });

        Route::prefix('transactions')->group( function () {
            Route::get('history', [TransactionController::class, 'index']);
        });
    });
});
