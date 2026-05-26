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
        Schema::create('interventoria_informes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_informe', 150);
            $table->string('periodo', 7); // YYYY-MM
            $table->text('aspectos_evaluados');
            $table->text('cumplimiento_indices');
            $table->decimal('costos_operacion', 15, 2)->nullable();
            $table->text('recomendaciones')->nullable();
            $table->text('compromisos_siguiente')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_informe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventoria_informes');
    }
};
