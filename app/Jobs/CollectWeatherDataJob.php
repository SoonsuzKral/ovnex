<?php
/*
 * OVNEX — Hava durumu verisi toplama işi
 * OpenWeatherMap'ten tüm şehirler için hava durumu verilerini alır
 */
namespace App\Jobs;

use App\Services\OpenWeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectWeatherDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60;

    public function handle(OpenWeatherService $weather): void
    {
        try {
            $weather->fetchAllCities();
        } catch (\Exception $e) {
            Log::error('CollectWeatherDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectWeatherDataJob basarisiz: ' . $exception->getMessage());
    }
}
