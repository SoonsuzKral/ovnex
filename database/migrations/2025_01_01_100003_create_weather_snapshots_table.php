<?php
/*
 * OVNEX — Hava durumu anlık verileri tablosu migration'ı
 * OpenWeatherMap API'den alınan şehir bazlı hava durumu verilerini saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('city', 100)->nullable(false)->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('temperature_c', 5, 2);
            $table->decimal('feels_like_c', 5, 2);
            $table->smallInteger('humidity_pct')->comment('0-100');
            $table->decimal('wind_speed_ms', 6, 2);
            $table->smallInteger('wind_direction')->comment('0-360 derece');
            $table->string('condition_code', 20);
            $table->string('condition_text', 100);
            $table->string('condition_icon', 200);
            $table->decimal('visibility_km', 6, 2);
            $table->decimal('pressure_hpa', 7, 2);
            $table->smallInteger('uv_index');
            $table->decimal('rainfall_mm', 6, 2)->default(0);
            $table->decimal('snow_mm', 6, 2)->default(0);
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_snapshots');
    }
};
