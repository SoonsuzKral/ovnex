<?php
/*
 * OVNEX — Trafik verisi toplama işi
 * TomTom API'den trafik olaylarını alır
 */
namespace App\Jobs;

use App\Services\TomTomTrafficService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectTrafficDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 30;

    public function handle(TomTomTrafficService $traffic): void
    {
        try {
            $traffic->fetchIncidents();
        } catch (\Exception $e) {
            Log::error('CollectTrafficDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectTrafficDataJob basarisiz: ' . $exception->getMessage());
    }
}
