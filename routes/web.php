<?php
/*
 * OVNEX — Web Rotaları
 * Ana sayfa dashboard'u render eder
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get('/', [MapController::class, 'dashboard']);
