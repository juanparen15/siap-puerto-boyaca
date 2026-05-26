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
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string('radicado', 20)->unique();
            $table->string('numero_cedula', 20)->index();
            $table->foreignId('elemento_id')->nullable()->constrained('infraestructura_elementos')->nullOnDelete();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->enum('tipo_solicitud', ['peticion', 'queja', 'reclamo', 'solicitud']);
            $table->text('descripcion');
            $table->string('nombre_ciudadano', 150);
            $table->string('email', 150)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->enum('estado', ['radicada', 'en_proceso', 'resuelta', 'cerrada'])->default('radicada');
            $table->text('accion_tomada')->nullable();
            $table->dateTime('fecha_respuesta')->nullable();
            $table->foreignId('funcionario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqrs');
    }
};
