<?php

use Illuminate\Support\Facades\Route;
use Modules\TitikDrainase\Http\Controllers\TitikDrainaseController;

Route::middleware('auth')->group(function () {
    Route::get('/titik-drainase', [TitikDrainaseController::class, 'index'])->name('titik-drainase.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/titik-drainase/create', [TitikDrainaseController::class, 'create'])->name('titik-drainase.create');
    Route::post('/titik-drainase', [TitikDrainaseController::class, 'store'])->name('titik-drainase.store');
    Route::get('/titik-drainase/{titikDrainase}/edit', [TitikDrainaseController::class, 'edit'])->name('titik-drainase.edit');
    Route::put('/titik-drainase/{titikDrainase}', [TitikDrainaseController::class, 'update'])->name('titik-drainase.update');
    Route::delete('/titik-drainase/{titikDrainase}', [TitikDrainaseController::class, 'destroy'])->name('titik-drainase.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/titik-drainase/{titikDrainase}', [TitikDrainaseController::class, 'show'])->name('titik-drainase.show');
});
