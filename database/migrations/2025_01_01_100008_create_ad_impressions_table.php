<?php
/*
 * OVNEX — Reklam görüntüleme ve tıklama sayaçları tablosu migration'ı
 * Reklam performans verilerini saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ad_unit', 50)->nullable(false);
            $table->string('ad_type', 20)->nullable();
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->string('country', 5)->nullable();
            $table->date('recorded_at')->nullable(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_impressions');
    }
};
