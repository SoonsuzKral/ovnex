<?php
/*
 * OVNEX — Gemi konum modeli
 * MarineTraffic API'den alınan gemi pozisyonlarını temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VesselPosition extends Model
{
    protected $fillable = [
        'mmsi',
        'vessel_name',
        'vessel_type',
        'flag',
        'latitude',
        'longitude',
        'speed_knots',
        'heading',
        'destination',
        'eta',
        'status',
        'recorded_at',
    ];

    protected $casts = [
        'eta' => 'datetime',
        'recorded_at' => 'datetime',
    ];
}
