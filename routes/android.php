<?php

use App\Http\Controllers\API\V1\Android\ApiController;
use Illuminate\Support\Facades\Route;

Route::namespace('App\\Http\\Controllers\\API\V1\\Android')->group(function () {
    Route::post("/android/device", [ApiController::class, 'device']);
    Route::post("/android/device/live", [ApiController::class, 'live']);
    Route::post("/android/stamp", [ApiController::class, 'stamp']);
    Route::post("/android/stamp/retry", [ApiController::class, 'retry']);
});
