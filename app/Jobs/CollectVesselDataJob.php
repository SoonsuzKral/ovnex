<?php
/*
 * OVNEX — Gemi verisi toplama işi
 * MarineTraffic API'den gemi konumlarını alır
 */
namespace App\Jobs;

use App\Services\MarineTrafficService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectVesselDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;

    public function handle(MarineTrafficService $marine): void
    {
        try {
            $marine->fetchVessels();
        } catch (\Exception $e) {
            Log::error('CollectVesselDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectVesselDataJob basarisiz: ' . $exception->getMessage());
    }
}
