<?php
/*
 * OVNEX — Uçak konum modeli
 * OpenSky API'den gelen anlık uçak verilerini temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AircraftPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'icao24',
        'callsign',
        'origin_country',
        'latitude',
        'longitude',
        'altitude_baro',
        'altitude_geo',
        'velocity',
        'heading',
        'vertical_rate',
        'on_ground',
        'squawk',
        'departure_airport',
        'arrival_airport',
        'aircraft_type',
        'recorded_at',
    ];

    protected $casts = [
        'on_ground' => 'boolean',
        'recorded_at' => 'datetime',
    ];
}
