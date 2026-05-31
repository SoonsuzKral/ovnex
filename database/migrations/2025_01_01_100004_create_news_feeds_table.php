<?php
/*
 * OVNEX — Haber ve olay akışı tablosu migration'ı
 * RSS kaynaklarından toplanan haberleri saklar
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_url', 500)->unique();
            $table->string('source_name', 100)->nullable(false);
            $table->string('source_type', 20)->nullable();
            $table->text('title')->nullable(false);
            $table->text('summary')->nullable();
            $table->string('category', 50)->default('general')->index();
            $table->string('severity', 20)->default('low');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('province', 100)->nullable()->index();
            $table->text('image_url')->nullable();
            $table->timestamp('published_at')->nullable(false)->index();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_feeds');
    }
};
