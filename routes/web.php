<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

//ESTUDIANTE
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    Route::get('/estudiante/dashboard', function () {
        return view('estudiante.dashboard');
    })->name('estudiante.dashboard');
});

require __DIR__.'/auth.php';
