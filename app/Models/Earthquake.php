<?php
/*
 * OVNEX — Deprem modeli
 * AFAD ve Kandilli'den alınan deprem kayıtlarını temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Earthquake extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'source',
        'latitude',
        'longitude',
        'depth_km',
        'magnitude',
        'magnitude_type',
        'location_name',
        'province',
        'district',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];
}
