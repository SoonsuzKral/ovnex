<?php
/*
 * OVNEX — Hava durumu modeli
 * OpenWeatherMap'ten alınan şehir bazlı hava durumu verilerini temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeatherSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'latitude',
        'longitude',
        'temperature_c',
        'feels_like_c',
        'humidity_pct',
        'wind_speed_ms',
        'wind_direction',
        'condition_code',
        'condition_text',
        'condition_icon',
        'visibility_km',
        'pressure_hpa',
        'uv_index',
        'rainfall_mm',
        'snow_mm',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];
}
