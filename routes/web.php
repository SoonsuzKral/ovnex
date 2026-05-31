<?php

use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// OVNEX — Ana sayfa dashboard
Route::get('/', [MapController::class, 'dashboard'])->name('dashboard');

// OVNEX — Admin sayfası (auth korumalı)
Route::get('/admin/stats', [MapController::class, 'adminStats'])
    ->middleware(['auth']);

// Breeze — Auth profili
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
