<?php
/*
 * OVNEX — Zamanlanmış Görevler (Scheduler)
 * Tüm veri toplama işlerinin frekansları burada tanımlanır
 */
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CollectAircraftDataJob;
use App\Jobs\CollectEarthquakeDataJob;
use App\Jobs\CollectWeatherDataJob;
use App\Jobs\CollectNewsDataJob;
use App\Jobs\CollectTrafficDataJob;
use App\Jobs\CollectVesselDataJob;

// Uçak: her 10 saniye
Schedule::job(new CollectAircraftDataJob)->everyTenSeconds();

// Deprem: her 1 dakika
Schedule::job(new CollectEarthquakeDataJob)->everyMinute();

// Hava durumu: her 15 dakika
Schedule::job(new CollectWeatherDataJob)->everyFifteenMinutes();

// Haber: her 5 dakika
Schedule::job(new CollectNewsDataJob)->everyFiveMinutes();

// Trafik: her 2 dakika
Schedule::job(new CollectTrafficDataJob)->everyTwoMinutes();

// Gemi: her 30 saniye
Schedule::job(new CollectVesselDataJob)->everyThirtySeconds();
