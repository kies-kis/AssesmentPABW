<?php

use Illuminate\Support\Facades\Route;
use Modules\Laporan\Http\Controllers\LaporanController;

Route::middleware('auth')->group(function () {
    Route::apiResource('laporan', LaporanController::class);
});
