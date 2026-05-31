<?php
/*
 * OVNEX — Hava durumu API kontrolcüsü
 * Hava durumu verilerini JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\WeatherSnapshot;

class WeatherController extends Controller
{
    public function current()
    {
        $weather = WeatherSnapshot::where('city', 'Şanlıurfa')
            ->latest('recorded_at')
            ->first();

        if (!$weather) {
            return response()->json(['error' => 'Hava durumu verisi bulunamadı'], 404);
        }

        return response()->json($weather);
    }

    public function cities()
    {
        $cities = WeatherSnapshot::select('city', 'temperature_c', 'feels_like_c', 'humidity_pct',
            'wind_speed_ms', 'condition_text', 'condition_icon', 'recorded_at')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')->from('weather_snapshots')->groupBy('city');
            })
            ->get();

        return response()->json($cities);
    }
}
