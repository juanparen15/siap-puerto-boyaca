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
        Schema::create('facturacion_periodos', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 7)->index(); // YYYY-MM
            $table->string('empresa_energetica', 150);
            $table->decimal('kwh_consumidos', 12, 3)->default(0);
            $table->decimal('valor_facturado', 15, 2)->default(0);
            $table->decimal('valor_pagado', 15, 2)->nullable();
            $table->date('fecha_factura');
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->enum('estado', ['pendiente', 'pagada', 'vencida'])->default('pendiente');
            $table->string('archivo_path', 255)->nullable();
            $table->boolean('extraido_por_ia')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_periodos');
    }
};
