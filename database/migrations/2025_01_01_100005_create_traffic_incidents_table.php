<?php
/*
 * OVNEX — Trafik olayları tablosu migration'ı
 * TomTom Traffic API'den alınan trafik olaylarını saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_incidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_id', 100)->unique()->nullable();
            $table->string('incident_type', 50)->nullable(false)->index();
            $table->smallInteger('severity')->comment('1=Hafif, 2=Orta, 3=Ağır, 4=Çok Ağır');
            $table->text('description')->nullable();
            $table->string('road_name', 200)->nullable();
            $table->decimal('start_lat', 10, 7);
            $table->decimal('start_lng', 10, 7);
            $table->decimal('end_lat', 10, 7)->nullable();
            $table->decimal('end_lng', 10, 7)->nullable();
            $table->integer('delay_seconds')->default(0);
            $table->string('province', 100)->nullable()->index();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_incidents');
    }
};
