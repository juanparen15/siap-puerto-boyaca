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
        Schema::create('infraestructura_elementos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['luminaria', 'poste', 'reflector', 'sendero_peatonal', 'campo_deportivo', 'luminaria_parque']);
            $table->string('rotulo', 50)->nullable()->index();
            $table->foreignId('red_id')->nullable()->constrained('infraestructura_redes')->nullOnDelete();
            $table->string('marca', 100)->nullable();
            $table->string('tecnologia', 50)->nullable();
            $table->integer('potencia_w')->nullable();
            $table->enum('estado', ['operativa', 'no_operativa', 'desinstalada'])->default('operativa');
            $table->string('tipo_poste', 50)->nullable();
            $table->decimal('altura_poste_m', 5, 2)->nullable();
            $table->integer('carga_rotura_kgf')->nullable();
            $table->enum('clasificacion', ['casco_urbano', 'puerto_serviez']);
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->date('fecha_levantamiento')->nullable();
            $table->string('globalid', 100)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infraestructura_elementos');
    }
};
