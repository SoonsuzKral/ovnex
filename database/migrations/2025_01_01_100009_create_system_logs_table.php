<?php
/*
 * OVNEX — Sistem logları tablosu migration'ı
 * API çağrıları ve sistem olaylarını kaydeder
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('service', 50)->nullable(false);
            $table->string('action', 50)->nullable();
            $table->string('status', 20)->nullable();
            $table->integer('records_fetched')->default(0);
            $table->integer('records_inserted')->default(0);
            $table->integer('duration_ms')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
