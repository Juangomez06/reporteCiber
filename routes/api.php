<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CasoController;
use App\Http\Controllers\Api\InstitucionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Reporte público vía API (soporta anónimo, igual que el formulario web)
Route::post('/casos', [CasoController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/casos', [CasoController::class, 'index']);
    Route::get('/casos/{caso}', [CasoController::class, 'show']);
    Route::patch('/casos/{caso}/estado', [CasoController::class, 'updateEstado']);
    Route::post('/casos/{caso}/asignar', [CasoController::class, 'asignar']);

    Route::apiResource('instituciones', InstitucionController::class)
        ->names('api.instituciones');
});