<?php
/*
 * OVNEX — Gemi konum verileri tablosu migration'ı
 * MarineTraffic API'den alınan gemi pozisyonlarını saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vessel_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mmsi', 15)->index();
            $table->string('vessel_name', 100)->nullable();
            $table->string('vessel_type', 50)->nullable();
            $table->string('flag', 5)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed_knots', 6, 2)->nullable();
            $table->smallInteger('heading')->nullable();
            $table->string('destination', 200)->nullable();
            $table->timestamp('eta')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamp('recorded_at')->nullable(false)->index();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_positions');
    }
};
