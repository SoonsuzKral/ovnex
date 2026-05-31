<?php
/*
 * OVNEX — Uçak konum verileri tablosu migration'ı
 * OpenSky Network API'den alınan anlık uçak konumlarını saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircraft_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icao24', 10)->index();
            $table->string('callsign', 20)->nullable();
            $table->string('origin_country', 50)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('altitude_baro', 10, 2)->nullable();
            $table->decimal('altitude_geo', 10, 2)->nullable();
            $table->decimal('velocity', 8, 2)->nullable();
            $table->decimal('heading', 5, 2)->nullable();
            $table->decimal('vertical_rate', 8, 2)->nullable();
            $table->boolean('on_ground')->default(false);
            $table->string('squawk', 10)->nullable();
            $table->string('departure_airport', 10)->nullable();
            $table->string('arrival_airport', 10)->nullable();
            $table->string('aircraft_type', 20)->nullable();
            $table->timestamp('recorded_at')->nullable(false)->index();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft_positions');
    }
};
