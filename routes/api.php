<?php
/*
 * OVNEX — API Rotaları
 * Tüm veri katmanlarına RESTful erişim sağlar
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AircraftController;
use App\Http\Controllers\EarthquakeController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\MapController;

Route::get('/aircraft', [AircraftController::class, 'index']);
Route::get('/aircraft/{icao24}', [AircraftController::class, 'show']);

Route::get('/earthquakes', [EarthquakeController::class, 'index']);
Route::get('/earthquakes/recent', [EarthquakeController::class, 'recent']);

Route::get('/weather/current', [WeatherController::class, 'current']);
Route::get('/weather/cities', [WeatherController::class, 'cities']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/latest', [NewsController::class, 'latest']);

Route::get('/traffic', [TrafficController::class, 'index']);

Route::get('/vessels', [VesselController::class, 'index']);

Route::get('/stats', [MapController::class, 'stats']);
