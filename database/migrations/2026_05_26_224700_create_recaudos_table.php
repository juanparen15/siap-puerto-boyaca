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
        Schema::create('recaudos', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 7); // YYYY-MM
            $table->string('concepto', 255);
            $table->decimal('valor_recaudado', 15, 2);
            $table->string('fuente_pago', 150);
            $table->date('fecha_recaudo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recaudos');
    }
};
