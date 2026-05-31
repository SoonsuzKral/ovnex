<?php
/*
 * OVNEX — TomTom Trafik servisi
 * TomTom Traffic API ile Türkiye geneli trafik olaylarını ve yoğunluk verilerini toplar
 */
namespace App\Services;

use App\Models\TrafficIncident;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TomTomTrafficService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.tomtom.com',
            'timeout'  => 15.0,
        ]);
    }

    public function fetchIncidents(): array
    {
        $basla = microtime(true);
        $records = [];
        $inserted = 0;

        try {
            $apiKey = env('TOMTOM_API_KEY');
            if (!$apiKey) throw new \Exception('TOMTOM_API_KEY tanimli degil');

            // Türkiye bounding box: kabaca 35.5-42.1 N, 25.0-44.8 E
            $bbox = '35.5,25.0,42.1,44.8';

            $response = $this->httpClient->get("/traffic/services/5/incidentDetails", [
                'query' => [
                    'key' => $apiKey,
                    'bbox' => $bbox,
                    'fields' => '{incidents}',
                    'language' => 'tr-TR',
                    't' => now()->timestamp,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $incidents = $data['incidents'] ?? [];
            $fetched = count($incidents);

            foreach ($incidents as $inc) {
                $extId = $inc['id'] ?? md5(json_encode($inc));
                $varsa = TrafficIncident::where('external_id', $extId)->exists();
                if ($varsa) continue;

                $props = $inc['properties'] ?? $inc;
                $geo = $inc['geometry'] ?? null;

                $startLat = $geo['coordinates'][0][1] ?? $props['lat'] ?? 0;
                $startLng = $geo['coordinates'][0][0] ?? $props['lng'] ?? 0;

                $records[] = [
                    'external_id'   => $extId,
                    'incident_type' => $props['iconCategory'] ?? 'alert',
                    'severity'      => $props['magnitudeOfDelay'] ?? 1,
                    'description'   => $props['description'] ?? ($props['event'] ?? null),
                    'road_name'     => $props['roadName'] ?? null,
                    'start_lat'     => $startLat,
                    'start_lng'     => $startLng,
                    'end_lat'       => $props['toLat'] ?? null,
                    'end_lng'       => $props['toLng'] ?? null,
                    'delay_seconds' => $props['delaySeconds'] ?? 0,
                    'province'      => $props['regionName'] ?? null,
                    'started_at'    => $props['startTime'] ?? now(),
                    'ended_at'      => $props['endTime'] ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $inserted++;
            }

            if (!empty($records)) {
                TrafficIncident::insert($records);
            }

            $this->logSuccess('tomtom', 'fetch', $fetched, $inserted, $basla);

        } catch (\Exception $e) {
            $this->logError('tomtom', 'fetch', $e->getMessage(), $basla);
            Log::error('TomTom API hatasi: ' . $e->getMessage());
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
