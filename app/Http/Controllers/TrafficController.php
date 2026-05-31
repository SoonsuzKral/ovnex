<?php
/*
 * OVNEX — Trafik API kontrolcüsü
 * Trafik olaylarını JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\TrafficIncident;

class TrafficController extends Controller
{
    public function index()
    {
        $incidents = TrafficIncident::where(function ($q) {
                $q->whereNull('ended_at')
                  ->orWhere('ended_at', '>=', now());
            })
            ->orderBy('severity', 'desc')
            ->get();

        return response()->json($incidents);
    }
}
