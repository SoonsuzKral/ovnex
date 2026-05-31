<?php
/*
 * OVNEX — Coğrafi kodlama servisi
 * Nominatim (OpenStreetMap) kullanarak metin adreslerini koordinata çevirir
 */
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://nominatim.openstreetmap.org',
            'timeout'  => 5.0,
            'headers'  => [
                'User-Agent' => 'OVNEX/1.0 (osman@ovnex.io)',
            ],
        ]);
    }

    public function geocode(string $query): ?array
    {
        try {
            $response = $this->httpClient->get('/search', [
                'query' => [
                    'q'       => $query,
                    'format'  => 'json',
                    'limit'   => 1,
                    'countrycodes' => 'tr',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (!empty($data[0])) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lon' => (float) $data[0]['lon'],
                    'display_name' => $data[0]['display_name'],
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Geocode basarisiz: {$query} - " . $e->getMessage());
        }

        return null;
    }

    public function reverseGeocode(float $lat, float $lon): ?string
    {
        try {
            $response = $this->httpClient->get('/reverse', [
                'query' => [
                    'lat'    => $lat,
                    'lon'    => $lon,
                    'format' => 'json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['display_name'] ?? null;

        } catch (\Exception $e) {
            Log::warning("Reverse geocode basarisiz: {$lat},{$lon} - " . $e->getMessage());
        }

        return null;
    }
}
