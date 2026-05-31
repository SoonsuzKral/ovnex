<?php
/*
 * OVNEX — Kamera kaynağı modeli
 * MOBESE ve belediye kameralarını temsil eder (Gelecek Faz)
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CameraSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_description',
        'latitude',
        'longitude',
        'stream_url',
        'thumbnail_url',
        'is_active',
        'province',
        'district',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
