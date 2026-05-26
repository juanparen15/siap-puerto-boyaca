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
        Schema::create('infraestructura_redes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->enum('tipo', ['alimentacion', 'canalizacion', 'transformador']);
            $table->enum('uso', ['exclusivo', 'compartido']);
            $table->enum('clasificacion', ['casco_urbano', 'puerto_serviez']);
            $table->string('material', 100)->nullable();
            $table->string('calibre_conductores', 50)->nullable();
            $table->enum('tipo_instalacion', ['aerea', 'subterranea'])->nullable();
            $table->enum('tipo_zona', ['dura', 'verde', 'cruce_calzada'])->nullable();
            $table->enum('tipo_transformador', ['aereo', 'local', 'pedestal', 'subterraneo'])->nullable();
            $table->decimal('potencia_kva', 8, 2)->nullable();
            $table->decimal('tension_primaria_kv', 6, 2)->nullable();
            $table->decimal('tension_secundaria_kv', 6, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infraestructura_redes');
    }
};
