<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pqrs_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pqrs_id')->constrained('pqrs')->cascadeOnDelete();
            $table->string('ruta');
            $table->string('nombre_original')->nullable();
            $table->string('mime', 120)->nullable();
            $table->unsignedBigInteger('tamano')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pqrs_adjuntos');
    }
};
