<?php

use App\Enums\TipoSolicitud;
use App\Models\Pqrs;
use App\Support\DiasHabiles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->dateTime('fecha_limite')->nullable()->after('fecha_respuesta');
        });

        // Backfill: calcular la fecha límite de los registros existentes
        Pqrs::query()->whereNull('fecha_limite')->chunkById(200, function ($pqrs) {
            foreach ($pqrs as $p) {
                $dias = TipoSolicitud::tryFrom((string) $p->tipo_solicitud)?->diasHabiles();
                if ($dias && $p->created_at) {
                    $p->fecha_limite = DiasHabiles::sumar($p->created_at, $dias);
                    $p->saveQuietly();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropColumn('fecha_limite');
        });
    }
};
