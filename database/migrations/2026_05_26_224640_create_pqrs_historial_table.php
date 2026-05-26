<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pqrs_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pqrs_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('estado_anterior', ['radicada', 'en_proceso', 'resuelta', 'cerrada'])->nullable();
            $table->enum('estado_nuevo', ['radicada', 'en_proceso', 'resuelta', 'cerrada']);
            $table->text('observacion')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqrs_historial');
    }
};
