<?php
/*
 * OVNEX — OpenSky Network servisi
 * Açık kaynak uçak takip API'sinden (opensky-network.org) Türkiye semalarındaki uçak verilerini toplar
 */
namespace App\Services;

use App\Models\AircraftPosition;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class OpenSkyService
{
    protected Client $httpClient;
    protected ?string $accessToken = null;
    protected int $tokenExpiresAt = 0;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://opensky-network.org',
            'timeout' => 15.0,
        ]);
    }

    protected function getAccessToken(): string
    {
        if ($this->accessToken && time() < $this->tokenExpiresAt) {
            return $this->accessToken;
        }

        $clientId = env('OPENSKY_CLIENT_ID');
        $clientSecret = env('OPENSKY_CLIENT_SECRET');

        if (!$clientId || !$clientSecret) {
            throw new \Exception('OPENSKY_CLIENT_ID veya OPENSKY_CLIENT_SECRET tanimli degil');
        }

        $response = $this->httpClient->post('/api/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $this->accessToken = $data['access_token'] ?? null;
        $expiresIn = $data['expires_in'] ?? 3600;
        $this->tokenExpiresAt = time() + $expiresIn - 60;

        if (!$this->accessToken) {
            throw new \Exception('OpenSky token alinamadi');
        }

        return $this->accessToken;
    }

    public function fetchAircraft(): array
    {
        $basla = microtime(true);
        $records = [];
        $fetched = 0;
        $inserted = 0;

        try {
            $token = $this->getAccessToken();

            $response = $this->httpClient->get('/api/states/all', [
                RequestOptions::QUERY => [
                    'lamin' => 35.5,
                    'lomin' => 25.0,
                    'lamax' => 42.1,
                    'lomax' => 44.8,
                ],
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            if ($response->getStatusCode() === 401) {
                $this->accessToken = null;
                $this->tokenExpiresAt = 0;
                $token = $this->getAccessToken();
                $response = $this->httpClient->get('/api/states/all', [
                    RequestOptions::QUERY => [
                        'lamin' => 35.5,
                        'lomin' => 25.0,
                        'lamax' => 42.1,
                        'lomax' => 44.8,
                    ],
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                ]);
            }

            $data = json_decode($response->getBody(), true);
            $states = $data['states'] ?? [];
            $fetched = count($states);

            $simdikiZaman = now();

            foreach ($states as $state) {
                $records[] = [
                    'icao24'         => $state[0] ?? null,
                    'callsign'       => $state[1] ?? null,
                    'origin_country' => $state[2] ?? null,
                    'longitude'      => $state[5] ?? null,
                    'latitude'       => $state[6] ?? null,
                    'altitude_baro'  => $state[7] ?? null,
                    'on_ground'      => $state[8] ?? false,
                    'velocity'       => $state[9] ?? null,
                    'heading'        => $state[10] ?? null,
                    'vertical_rate'  => $state[11] ?? null,
                    'recorded_at'    => $simdikiZaman,
                    'created_at'     => $simdikiZaman,
                    'updated_at'     => $simdikiZaman,
                ];
            }

            if (!empty($records)) {
                AircraftPosition::insert($records);
                $inserted = count($records);
            }

            $this->logSuccess('opensky', 'fetch', $fetched, $inserted, $basla);

        } catch (\Exception $e) {
            $this->logError('opensky', 'fetch', $e->getMessage(), $basla);
            Log::error('OpenSky API hatasi: ' . $e->getMessage());
        }

        return $records;
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
