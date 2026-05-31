<?php
/*
 * OVNEX — Deprem verisi toplama işi
 * AFAD API'den deprem verilerini alır, 4.0+ için EarthquakeDetected event'ini tetikler
 */
namespace App\Jobs;

use App\Services\AfadEarthquakeService;
use App\Events\EarthquakeDetected;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectEarthquakeDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;

    public function handle(AfadEarthquakeService $afad): void
    {
        try {
            $earthquakes = $afad->fetchEarthquakes();

            foreach ($earthquakes as $eq) {
                if (($eq['magnitude'] ?? 0) >= 4.0) {
                    EarthquakeDetected::dispatch($eq);
                }
            }
        } catch (\Exception $e) {
            Log::error('CollectEarthquakeDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectEarthquakeDataJob basarisiz: ' . $exception->getMessage());
    }
}
