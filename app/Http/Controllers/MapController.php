<?php
/*
 * OVNEX — Harita kontrolcüsü
 * Ana dashboard sayfasını render eder
 */
namespace App\Http\Controllers;

use App\Models\AircraftPosition;
use App\Models\Earthquake;
use App\Models\WeatherSnapshot;
use App\Models\NewsFeed;
use App\Models\TrafficIncident;
use App\Models\VesselPosition;
use App\Models\SystemLog;

class MapController extends Controller
{
    public function dashboard()
    {
        $sonUcaklar = AircraftPosition::where('recorded_at', '>=', now()->subMinute())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();

        $sonDeprem = Earthquake::where('occurred_at', '>=', now()->subDay())
            ->orderBy('magnitude', 'desc')
            ->first();

        $sonHava = WeatherSnapshot::where('city', 'Şanlıurfa')
            ->latest('recorded_at')
            ->first();

        $sonHaberler = NewsFeed::latest('published_at')
            ->take(10)
            ->get();

        $aktifTrafik = TrafficIncident::whereNull('ended_at')
            ->orWhere('ended_at', '>=', now())
            ->count();

        $aktifGemiler = VesselPosition::where('recorded_at', '>=', now()->subHour())
            ->count();

        return view('pages.dashboard', compact(
            'sonUcaklar',
            'sonDeprem',
            'sonHava',
            'sonHaberler',
            'aktifTrafik',
            'aktifGemiler'
        ));
    }

    public function stats()
    {
        $data = [
            'active_aircraft' => AircraftPosition::where('recorded_at', '>=', now()->subMinute())->count(),
            'last_earthquake' => Earthquake::where('occurred_at', '>=', now()->subDay())->max('magnitude'),
            'weather_temp'    => WeatherSnapshot::where('city', 'Şanlıurfa')->latest('recorded_at')->value('temperature_c'),
            'active_traffic'  => TrafficIncident::whereNull('ended_at')->count(),
            'active_vessels'  => VesselPosition::where('recorded_at', '>=', now()->subHour())->count(),
            'total_news'      => NewsFeed::whereDate('created_at', today())->count(),
        ];

        return response()->json($data);
    }

    public function adminStats()
    {
        $sonLoglar = SystemLog::latest('created_at')
            ->take(50)
            ->get();

        return view('pages.admin-stats', compact('sonLoglar'));
    }
}
