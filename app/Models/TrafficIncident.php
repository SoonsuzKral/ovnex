<?php
/*
 * OVNEX — Trafik olayı modeli
 * TomTom Traffic API'den alınan trafik olaylarını temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrafficIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'incident_type',
        'severity',
        'description',
        'road_name',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'delay_seconds',
        'province',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
