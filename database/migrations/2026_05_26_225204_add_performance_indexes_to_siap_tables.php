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
        Schema::table('infraestructura_elementos', function (Blueprint $table) {
            $table->index(['estado', 'clasificacion']);
            $table->index(['latitud', 'longitud']);
        });

        Schema::table('pqrs', function (Blueprint $table) {
            $table->index('estado');
            $table->index('created_at');
        });

        Schema::table('recaudos', function (Blueprint $table) {
            $table->index('periodo');
        });

        Schema::table('interventoria_informes', function (Blueprint $table) {
            $table->index('periodo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infraestructura_elementos', function (Blueprint $table) {
            $table->dropIndex(['estado', 'clasificacion']);
            $table->dropIndex(['latitud', 'longitud']);
        });

        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('recaudos', function (Blueprint $table) {
            $table->dropIndex(['periodo']);
        });

        Schema::table('interventoria_informes', function (Blueprint $table) {
            $table->dropIndex(['periodo']);
        });
    }
};
