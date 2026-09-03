<?php

use App\Http\Controllers\CasoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Reporte de casos: público, soporta anónimo
Route::get('/reportar', [CasoController::class, 'create'])->name('casos.reportar');
Route::post('/reportar', [CasoController::class, 'store'])->name('casos.store');
Route::get('/reportar/confirmacion/{codigo}', [CasoController::class, 'confirmacion'])->name('casos.confirmacion');

Route::get('/dashboard', function () {
    $user = auth()->user();

    return redirect($user->isCoordinador() ? route('coordinador.dashboard') : route('estudiante.dashboard'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestión de casos (coordinador y estudiante/orientador, filtrado por policy)
    Route::get('/casos', [CasoController::class, 'index'])->name('casos.index');
    Route::get('/casos/exportar/csv', [ExportController::class, 'csv'])->name('casos.export.csv');
    Route::get('/casos/exportar/pdf', [ExportController::class, 'pdf'])->name('casos.export.pdf');
    Route::get('/casos/{caso}', [CasoController::class, 'show'])->name('casos.show');
    Route::patch('/casos/{caso}/estado', [CasoController::class, 'actualizarEstado'])->name('casos.estado');
    Route::post('/casos/{caso}/asignar', [CasoController::class, 'asignar'])->name('casos.asignar');
    Route::post('/casos/{caso}/notas', [CasoController::class, 'agregarNota'])->name('casos.notas.store');
});

// COORDINADOR
Route::middleware(['auth', 'role:coordinador'])->group(function () {
    Route::get('/coordinador/dashboard', [DashboardController::class, 'coordinador'])->name('coordinador.dashboard');
    Route::resource('instituciones', InstitucionController::class)->except('show');
});

//ESTUDIANTE
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    Route::get('/estudiante/dashboard', [DashboardController::class, 'estudiante'])->name('estudiante.dashboard');
});

require __DIR__.'/auth.php';
