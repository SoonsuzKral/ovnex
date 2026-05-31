<?php
/*
 * OVNEX — Haber verisi toplama işi
 * RSS kaynaklarından haberleri toplar ve NewsReceived event'ini tetikler
 */
namespace App\Jobs;

use App\Services\RssNewsService;
use App\Events\NewsReceived;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectNewsDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60;

    public function handle(RssNewsService $rss): void
    {
        try {
            $news = $rss->fetchAll();

            if (!empty($news)) {
                NewsReceived::dispatch($news);
            }
        } catch (\Exception $e) {
            Log::error('CollectNewsDataJob hatasi: ' . $e->getMessage());
            $this->failed($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CollectNewsDataJob basarisiz: ' . $exception->getMessage());
    }
}
