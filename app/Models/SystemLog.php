<?php
/*
 * OVNEX — Sistem log modeli
 * API çağrıları ve sistem olaylarının kaydını temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service',
        'action',
        'status',
        'records_fetched',
        'records_inserted',
        'duration_ms',
        'error_message',
    ];
}
