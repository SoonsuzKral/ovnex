<?php
namespace App\Console\Commands;

use App\Services\OpenSkyService;
use App\Services\AfadEarthquakeService;
use App\Services\OpenWeatherService;
use App\Services\RssNewsService;
use App\Services\TomTomTrafficService;
use App\Services\MarineTrafficService;
use Illuminate\Console\Command;

class CollectSyncCommand extends Command
{
    protected $signature = 'ovnex:collect-sync';
    protected $description = 'Tum veri kaynaklarini senkron olarak toplar (queue gerektirmez)';

    public function handle(
        OpenSkyService $openSky,
        AfadEarthquakeService $earthquake,
        OpenWeatherService $weather,
        RssNewsService $news,
        TomTomTrafficService $traffic,
        MarineTrafficService $marine,
    ): void {
        $this->info('OVNEX senkron veri toplama basliyor...');
        $counts = [];

        $this->line('Ucak verisi cekiliyor...');
        $counts['aircraft'] = count($openSky->fetchAircraft());

        $this->line('Deprem verisi cekiliyor...');
        $counts['earthquake'] = count($earthquake->fetchEarthquakes());

        $this->line('Hava durumu verisi cekiliyor...');
        $counts['weather'] = count($weather->fetchAllCities());

        $this->line('Haber verisi cekiliyor...');
        $counts['news'] = count($news->fetchAll());

        $this->line('Trafik verisi cekiliyor...');
        $counts['traffic'] = count($traffic->fetchIncidents());

        $this->line('Gemi verisi cekiliyor...');
        $counts['vessels'] = count($marine->fetchVessels());

        $this->table(
            ['Kaynak', 'Kayit'],
            array_map(fn($k, $v) => [$k, $v], array_keys($counts), $counts)
        );

        $this->info('Veri toplama tamamlandi.');
    }
}
