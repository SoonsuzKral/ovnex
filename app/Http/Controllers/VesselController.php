<?php
/*
 * OVNEX — Gemi API kontrolcüsü
 * Gemi konum verilerini JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\VesselPosition;

class VesselController extends Controller
{
    public function index()
    {
        $vessels = VesselPosition::where('recorded_at', '>=', now()->subHour())
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json($vessels);
    }
}
