<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LicenseController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// License API Routes
Route::prefix('license')->group(function () {
    Route::post('/activate', [LicenseController::class, 'activate']);
    Route::post('/validate', [LicenseController::class, 'validate']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/generate', [LicenseController::class, 'generate']);
        Route::get('/list', [LicenseController::class, 'list']);
        Route::post('/suspend/{licenseKey}', [LicenseController::class, 'suspend']);
    });
});