<?php
/*
 * OVNEX — MarineTraffic servisi
 * Deniz araçları konum verilerini MarineTraffic API'den toplar
 */
namespace App\Services;

use App\Models\VesselPosition;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MarineTrafficService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://services.marinetraffic.com',
            'timeout'  => 15.0,
        ]);
    }

    public function fetchVessels(): array
    {
        $basla = microtime(true);
        $records = [];
        $inserted = 0;

        try {
            $apiKey = env('MARINE_TRAFFIC_API_KEY');
            if (!$apiKey) throw new \Exception('MARINE_TRAFFIC_API_KEY tanimli degil');

            // Türkiye kıyıları bounding box
            $response = $this->httpClient->get('/api/exportvessel/v2/', [
                'query' => [
                    'api_key'    => $apiKey,
                    'protocol'   => 'json',
                    'min_lat'    => 35.5,
                    'max_lat'    => 42.5,
                    'min_lon'    => 25.0,
                    'max_lon'    => 45.0,
                    'ship_type'  => '',
                    'show_etd'   => 'true',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $vessels = is_array($data) ? $data : [];
            $fetched = count($vessels);

            $simdi = now();

            foreach ($vessels as $v) {
                $mmsi = $v['MMSI'] ?? $v['mmsi'] ?? null;
                if (!$mmsi) continue;

                $records[] = [
                    'mmsi'         => (string) $mmsi,
                    'vessel_name'  => $v['SHIPNAME'] ?? $v['vessel_name'] ?? null,
                    'vessel_type'  => $v['SHIPTYPE'] ?? $v['vessel_type'] ?? null,
                    'flag'         => $v['FLAG'] ?? $v['flag'] ?? null,
                    'latitude'     => $v['LAT'] ?? $v['latitude'] ?? 0,
                    'longitude'    => $v['LON'] ?? $v['longitude'] ?? 0,
                    'speed_knots'  => $v['SPEED'] ?? $v['speed_knots'] ?? null,
                    'heading'      => $v['HEADING'] ?? $v['heading'] ?? null,
                    'destination'  => $v['DESTINATION'] ?? $v['destination'] ?? null,
                    'eta'          => $v['ETA'] ?? $v['eta'] ?? null,
                    'status'       => $v['STATUS'] ?? $v['status'] ?? null,
                    'recorded_at'  => $simdi,
                    'created_at'   => $simdi,
                    'updated_at'   => $simdi,
                ];

                $inserted++;
            }

            if (!empty($records)) {
                foreach (array_chunk($records, 50) as $chunk) {
                    VesselPosition::insert($chunk);
                }
            }

            $this->logSuccess('marinetraffic', 'fetch', $fetched, $inserted, $basla);

        } catch (\Exception $e) {
            $this->logError('marinetraffic', 'fetch', $e->getMessage(), $basla);
            Log::error('MarineTraffic API hatasi: ' . $e->getMessage());
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
