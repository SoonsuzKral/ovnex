<?php
/*
 * OVNEX — Reklam görüntüleme modeli
 * Reklam performans ve gösterim verilerini temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdImpression extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_unit',
        'ad_type',
        'impressions',
        'clicks',
        'ip_hash',
        'user_agent_hash',
        'country',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'date',
    ];
}
