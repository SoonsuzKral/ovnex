<?php
/*
 * OVNEX — Sistem log modeli
 * API çağrıları ve sistem olaylarının kaydını temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'service',
        'action',
        'status',
        'records_fetched',
        'records_inserted',
        'duration_ms',
        'error_message',
        'created_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (SystemLog $log) {
            if (!$log->created_at) {
                $log->created_at = now();
            }
        });
    }
}
