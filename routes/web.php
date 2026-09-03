<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstudianteImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirigir a la ruta correspondiente según el rol del usuario después de iniciar sesión
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isCoordinador()) {
        return redirect()->route('coordinador.dashboard');
    } elseif ($user->isEstudiante()) {
        return redirect()->route('estudiante.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// AUTENTICACIÓN
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// COORDINADOR
Route::middleware(['auth', 'role:coordinador'])->group(function () {
    Route::get('/coordinador/dashboard', function () {
        return view('coordinador.dashboard');
    })->name('coordinador.dashboard');

    // Rutas de importación de estudiantes
    Route::get('/estudiantes/importar', [EstudianteImportController::class, 'showForm'])
        ->name('estudiantes.importar');
    Route::post('/estudiantes/importar', [EstudianteImportController::class, 'import'])
        ->name('estudiantes.importar.post');
});

//ESTUDIANTE
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    Route::get('/estudiante/dashboard', function () {
        return view('estudiante.dashboard');
    })->name('estudiante.dashboard');
});

require __DIR__.'/auth.php';
