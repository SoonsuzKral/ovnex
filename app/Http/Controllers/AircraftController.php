<?php
/*
 * OVNEX — Uçak API kontrolcüsü
 * Uçak konum verilerini JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\AircraftPosition;

class AircraftController extends Controller
{
    public function index()
    {
        $aircraft = AircraftPosition::where('recorded_at', '>=', now()->subMinute())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json($aircraft);
    }

    public function show(string $icao24)
    {
        $aircraft = AircraftPosition::where('icao24', $icao24)
            ->latest('recorded_at')
            ->first();

        if (!$aircraft) {
            return response()->json(['error' => 'Uçak bulunamadı'], 404);
        }

        return response()->json($aircraft);
    }
}
