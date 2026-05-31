<?php
/*
 * OVNEX — Uçak verisi toplama işi
 * OpenSky API'den uçak konumlarını alır ve AircraftUpdated event'ini tetikler
 */
namespace App\Jobs;

use App\Services\OpenSkyService;
use App\Events\AircraftUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectAircraftDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;

    public function handle(OpenSkyService $openSky): void
    {
        try {
            $aircraft = $openSky->fetchAircraft();

            if (!empty($aircraft)) {
                AircraftUpdated::dispatch($aircraft);
            }
        } catch (\Exception $e) {
            Log::error('CollectAircraftDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectAircraftDataJob basarisiz oldu: ' . $exception->getMessage());
    }
}
