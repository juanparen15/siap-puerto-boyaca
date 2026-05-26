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
        Schema::create('financiamiento_recursos', function (Blueprint $table) {
            $table->id();
            $table->string('fuente', 255);
            $table->string('tipo_recurso', 100);
            $table->decimal('valor', 15, 2);
            $table->text('destinacion');
            $table->date('fecha_recepcion');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financiamiento_recursos');
    }
};
