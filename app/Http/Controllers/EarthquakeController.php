<?php
/*
 * OVNEX — Deprem API kontrolcüsü
 * Deprem verilerini JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\Earthquake;

class EarthquakeController extends Controller
{
    public function index()
    {
        $query = Earthquake::where('occurred_at', '>=', now()->subDay());

        if (request()->has('min_magnitude')) {
            $query->where('magnitude', '>=', request('min_magnitude'));
        }

        $earthquakes = $query->orderBy('occurred_at', 'desc')->get();

        return response()->json($earthquakes);
    }

    public function recent()
    {
        $earthquakes = Earthquake::orderBy('occurred_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($earthquakes);
    }
}
