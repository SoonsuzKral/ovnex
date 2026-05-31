<?php
/*
 * OVNEX — Deprem kayıtları tablosu migration'ı
 * AFAD ve Kandilli Rasathanesi'nden alınan deprem verilerini saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('earthquakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_id', 100)->unique()->comment('AFAD olay ID');
            $table->string('source', 20)->default('AFAD');
            $table->decimal('latitude', 10, 7)->nullable(false);
            $table->decimal('longitude', 10, 7)->nullable(false);
            $table->decimal('depth_km', 8, 2)->nullable();
            $table->decimal('magnitude', 4, 2)->nullable(false)->index();
            $table->string('magnitude_type', 10)->nullable();
            $table->string('location_name', 200)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->timestamp('occurred_at')->nullable(false)->index();
            $table->timestamps();

            $table->index('province');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('earthquakes');
    }
};
