<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchuleController;
use App\Http\Controllers\SchulerController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\AusbildungController;
use App\Http\Livewire\Dashboard;
use Livewire\Livewire;

// Redirection racine
Route::redirect('/', '/login');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Routes protégées
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Schüler Routes
    Route::prefix('schulers')->group(function () {
        Route::get('/', [SchulerController::class, 'index'])->name('schulers.index');
        Route::get('/{id}', [SchulerController::class, 'show'])->name('schulers.show');
    });

    // Schule Routes
    Route::prefix('schulen')->group(function () {
        Route::get('/', [SchuleController::class, 'index'])->name('schulen.index');
        Route::get('/{id}', [SchuleController::class, 'show'])->name('schulen.show');
    });

    // Firma Routes
    Route::prefix('firmen')->group(function () {
        Route::get('/', [FirmaController::class, 'index'])->name('firmen.index');
        Route::get('/{firma}', [FirmaController::class, 'show'])->name('firmen.show');
    });

    // Ausbildung Routes
    Route::resource('ausbildungs', AusbildungController::class)
        ->except(['create', 'edit']);

    Route::get('/dossiers/create', function () {
        return view('dossiers.create');
    })->name('dossiers.create');

    Route::get('/dossiers/{id}', function ($id) {
        return view('dossiers.show', ['id' => $id]);
    })->name('dossiers.show');

    Route::get('/dossiers/{id}/edit', function ($id) {
        return view('dossiers.edit', ['id' => $id]);
    })->name('dossiers.edit');

    Route::get('/test', function () {
        return view('test');
    });
});