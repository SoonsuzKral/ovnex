<?php
/*
 * OVNEX — OpenWeatherMap servisi
 * Türkiye'deki ana şehirler için anlık hava durumu verilerini toplar
 */
namespace App\Services;

use App\Models\WeatherSnapshot;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenWeatherService
{
    protected Client $httpClient;

    protected array $cities = [
        'Şanlıurfa' => [37.1591, 38.7969],
        'Gaziantep' => [37.0662, 37.3833],
        'Diyarbakır' => [37.9144, 40.2306],
        'Ankara'    => [39.9334, 32.8597],
        'İstanbul'  => [41.0082, 28.9784],
    ];

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openweathermap.org',
            'timeout'  => 10.0,
        ]);
    }

    public function fetchAllCities(): array
    {
        $results = [];

        foreach ($this->cities as $sehir => [$lat, $lon]) {
            try {
                $sonuc = $this->fetchCity($sehir, $lat, $lon);
                if ($sonuc) $results[] = $sonuc;
            } catch (\Exception $e) {
                Log::warning("OpenWeather {$sehir} basarisiz: " . $e->getMessage());
            }
        }

        return $results;
    }

    public function fetchCity(string $sehir, float $lat, float $lon): ?WeatherSnapshot
    {
        $basla = microtime(true);

        try {
            $apiKey = env('OPENWEATHER_API_KEY');
            if (!$apiKey) throw new \Exception('OPENWEATHER_API_KEY tanimli degil');

            $response = $this->httpClient->get('/data/2.5/weather', [
                'query' => [
                    'lat'   => $lat,
                    'lon'   => $lon,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang'  => 'tr',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $record = WeatherSnapshot::create([
                'city'           => $sehir,
                'latitude'       => $lat,
                'longitude'      => $lon,
                'temperature_c'  => $data['main']['temp'] ?? 0,
                'feels_like_c'   => $data['main']['feels_like'] ?? 0,
                'humidity_pct'   => $data['main']['humidity'] ?? 0,
                'wind_speed_ms'  => $data['wind']['speed'] ?? 0,
                'wind_direction' => $data['wind']['deg'] ?? 0,
                'condition_code' => $data['weather'][0]['main'] ?? '',
                'condition_text' => $data['weather'][0]['description'] ?? '',
                'condition_icon' => $data['weather'][0]['icon'] ?? '',
                'visibility_km'  => ($data['visibility'] ?? 0) / 1000,
                'pressure_hpa'   => $data['main']['pressure'] ?? 0,
                'uv_index'       => 0,
                'rainfall_mm'    => $data['rain']['1h'] ?? 0,
                'snow_mm'        => $data['snow']['1h'] ?? 0,
                'recorded_at'    => now(),
            ]);

            $this->logSuccess('openweather', "fetch_{$sehir}", 1, 1, $basla);
            return $record;

        } catch (\Exception $e) {
            $this->logError('openweather', "fetch_{$sehir}", $e->getMessage(), $basla);
            return null;
        }
    }

    protected function logSuccess(string $service, string $action, int $fetched, int $inserted, float $basla): void
    {
        SystemLog::create([
            'service'          => $service,
            'action'           => $action,
            'status'           => 'success',
            'records_fetched'  => $fetched,
            'records_inserted' => $inserted,
            'duration_ms'      => (int) ((microtime(true) - $basla) * 1000),
        ]);
    }

    protected function logError(string $service, string $action, string $error, float $basla): void
    {
        SystemLog::create([
            'service'        => $service,
            'action'         => $action,
            'status'         => 'failed',
            'duration_ms'    => (int) ((microtime(true) - $basla) * 1000),
            'error_message'  => $error,
        ]);
    }
}
