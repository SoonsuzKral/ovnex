<?php
/*
 * OVNEX — Şehir kameraları tablosu migration'ı
 * MOBESE ve belediye kamera kaynaklarını saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->nullable(false);
            $table->text('location_description')->nullable();
            $table->decimal('latitude', 10, 7)->nullable(false);
            $table->decimal('longitude', 10, 7)->nullable(false);
            $table->text('stream_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('province', 100)->default('Şanlıurfa');
            $table->string('district', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camera_sources');
    }
};
