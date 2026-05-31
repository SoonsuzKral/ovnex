<?php
/*
 * OVNEX — AFAD Deprem servisi
 * AFAD Open API ve Kandilli RSS'den Türkiye geneli deprem verilerini toplar
 */
namespace App\Services;

use App\Models\Earthquake;
use App\Models\SystemLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AfadEarthquakeService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 15.0,
        ]);
    }

    public function fetchEarthquakes(): array
    {
        $basla = microtime(true);
        $records = [];
        $fetched = 0;
        $inserted = 0;

        try {
            $simdi = now()->format('Y-m-d H:i:s');
            $birSaatOnce = now()->subHour()->format('Y-m-d H:i:s');

            $response = $this->httpClient->get(env('AFAD_API_URL', 'https://deprem.afad.gov.tr/apiv2/event/filter'), [
                'query' => [
                    'start'   => $birSaatOnce,
                    'end'     => $simdi,
                    'orderby' => 'timedesc',
                    'limit'   => 50,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $events = $data ?? [];
            $fetched = count($events);

            foreach ($events as $event) {
                $externalId = $event['id'] ?? $event['eventID'] ?? null;
                if (!$externalId) continue;

                $varsa = Earthquake::where('external_id', $externalId)->exists();
                if ($varsa) continue;

                $record = [
                    'external_id'    => $externalId,
                    'source'         => 'AFAD',
                    'latitude'       => $event['latitude'] ?? $event['lat'] ?? 0,
                    'longitude'      => $event['longitude'] ?? $event['lng'] ?? 0,
                    'depth_km'       => $event['depth'] ?? $event['depth_km'] ?? null,
                    'magnitude'      => $event['magnitude'] ?? $event['mag'] ?? 0,
                    'magnitude_type' => $event['magnitudeType'] ?? $event['type'] ?? null,
                    'location_name'  => $event['location'] ?? $event['locationName'] ?? null,
                    'province'       => $event['province'] ?? null,
                    'district'       => $event['district'] ?? null,
                    'occurred_at'    => $event['date'] ?? $event['occurred_at'] ?? now(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                $records[] = $record;
                $inserted++;
            }

            if (!empty($records)) {
                Earthquake::insert($records);
            }

            $this->logSuccess('afad', 'fetch', $fetched, $inserted, $basla);

        } catch (\Exception $e) {
            $this->logError('afad', 'fetch', $e->getMessage(), $basla);
            Log::error('AFAD API hatasi: ' . $e->getMessage());
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
