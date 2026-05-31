<?php
namespace App\Services;

use App\Models\VesselPosition;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AisHubService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://www.aishub.net',
            'timeout'  => 15.0,
        ]);
    }

    public function fetchVessels(): array
    {
        $basla = microtime(true);
        $records = [];
        $inserted = 0;

        try {
            $username = env('AISHUB_USERNAME');
            if (!$username) {
                Log::info('AISHUB_USERNAME tanimli degil, atlaniyor');
                return [];
            }

            $response = $this->httpClient->get('/api/ais-data', [
                'query' => [
                    'username' => $username,
                    'format'   => 1,
                    'lat_min'  => 35.5,
                    'lat_max'  => 42.5,
                    'lon_min'  => 25.0,
                    'lon_max'  => 45.0,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $vessels = $data['data'] ?? is_array($data) ? $data : [];
            $fetched = count($vessels);
            $simdi = now();

            foreach ($vessels as $v) {
                $mmsi = $v['MMSI'] ?? $v['mmsi'] ?? null;
                if (!$mmsi) continue;

                $records[] = [
                    'mmsi'         => (string) $mmsi,
                    'vessel_name'  => $v['SHIPNAME'] ?? $v['name'] ?? null,
                    'vessel_type'  => $v['SHIPTYPE'] ?? $v['type'] ?? null,
                    'flag'         => $v['FLAG'] ?? $v['flag'] ?? null,
                    'latitude'     => $v['LAT'] ?? $v['lat'] ?? 0,
                    'longitude'    => $v['LON'] ?? $v['lon'] ?? 0,
                    'speed_knots'  => $v['SPEED'] ?? $v['speed'] ?? null,
                    'heading'      => $v['HEADING'] ?? $v['heading'] ?? null,
                    'destination'  => $v['DESTINATION'] ?? $v['destination'] ?? null,
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

            SystemLog::create([
                'service'          => 'aishub',
                'action'           => 'fetch',
                'status'           => 'success',
                'records_fetched'  => $fetched,
                'records_inserted' => $inserted,
                'duration_ms'      => (int) ((microtime(true) - $basla) * 1000),
            ]);

        } catch (\Exception $e) {
            SystemLog::create([
                'service'       => 'aishub',
                'action'        => 'fetch',
                'status'        => 'failed',
                'duration_ms'   => (int) ((microtime(true) - $basla) * 1000),
                'error_message' => $e->getMessage(),
            ]);
            Log::error('AISHub API hatasi: ' . $e->getMessage());
        }

        return $records;
    }
}
