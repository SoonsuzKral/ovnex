<?php
/*
 * OVNEX — Tüm verileri toplama komutu
 * Tek seferde tüm servisleri tetikler (manuel kullanım için)
 */
namespace App\Console\Commands;

use App\Jobs\CollectAircraftDataJob;
use App\Jobs\CollectEarthquakeDataJob;
use App\Jobs\CollectWeatherDataJob;
use App\Jobs\CollectNewsDataJob;
use App\Jobs\CollectTrafficDataJob;
use App\Jobs\CollectVesselDataJob;
use Illuminate\Console\Command;

class CollectAllDataCommand extends Command
{
    protected $signature = 'ovnex:collect-all';
    protected $description = 'Tüm OVNEX veri kaynaklarını toplar';

    public function handle(): void
    {
        $this->info('OVNEX veri toplama başlıyor...');

        CollectAircraftDataJob::dispatch();
        $this->line('✓ Uçak verisi kuyruğa eklendi');

        CollectEarthquakeDataJob::dispatch();
        $this->line('✓ Deprem verisi kuyruğa eklendi');

        CollectWeatherDataJob::dispatch();
        $this->line('✓ Hava durumu verisi kuyruğa eklendi');

        CollectNewsDataJob::dispatch();
        $this->line('✓ Haber verisi kuyruğa eklendi');

        CollectTrafficDataJob::dispatch();
        $this->line('✓ Trafik verisi kuyruğa eklendi');

        CollectVesselDataJob::dispatch();
        $this->line('✓ Gemi verisi kuyruğa eklendi');

        $this->info('Tüm veriler kuyruğa eklendi. php artisan queue:work ile işleyin.');
    }
}
