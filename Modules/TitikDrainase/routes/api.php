<?php

use Illuminate\Support\Facades\Route;
use Modules\TitikDrainase\Http\Controllers\TitikDrainaseController;

Route::middleware('auth')->group(function () {
    Route::apiResource('titik-drainase', TitikDrainaseController::class);
});
