<?php
/*
 * OVNEX — Haber akışı modeli
 * RSS kaynaklarından toplanan haber ve olayları temsil eder
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    protected $fillable = [
        'external_url',
        'source_name',
        'source_type',
        'title',
        'summary',
        'category',
        'severity',
        'latitude',
        'longitude',
        'province',
        'image_url',
        'published_at',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'published_at' => 'datetime',
    ];
}
