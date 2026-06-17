<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pasar de ENUM a VARCHAR: los valores válidos se controlan en PHP (enums),
        // así agregar/ajustar estados o tipos no requiere nuevas migraciones de esquema.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pqrs MODIFY tipo_solicitud VARCHAR(30) NOT NULL");
            DB::statement("ALTER TABLE pqrs MODIFY estado VARCHAR(20) NOT NULL DEFAULT 'radicada'");
            DB::statement("ALTER TABLE pqrs_historial MODIFY estado_anterior VARCHAR(20) NULL");
            DB::statement("ALTER TABLE pqrs_historial MODIFY estado_nuevo VARCHAR(20) NOT NULL");
        }

        // Mapear vocabulario antiguo → PQRSD profesional
        DB::table('pqrs')->where('tipo_solicitud', 'solicitud')->update(['tipo_solicitud' => 'peticion']);
        DB::table('pqrs')->where('estado', 'en_proceso')->update(['estado' => 'en_tramite']);
        DB::table('pqrs')->where('estado', 'resuelta')->update(['estado' => 'respondida']);

        foreach (['estado_anterior', 'estado_nuevo'] as $col) {
            DB::table('pqrs_historial')->where($col, 'en_proceso')->update([$col => 'en_tramite']);
            DB::table('pqrs_historial')->where($col, 'resuelta')->update([$col => 'respondida']);
        }
    }

    public function down(): void
    {
        // Revertir solo el mapeo de datos (las columnas permanecen como VARCHAR)
        DB::table('pqrs')->where('estado', 'en_tramite')->update(['estado' => 'en_proceso']);
        DB::table('pqrs')->where('estado', 'respondida')->update(['estado' => 'resuelta']);
        DB::table('pqrs')->where('tipo_solicitud', 'peticion')->update(['tipo_solicitud' => 'solicitud']);
    }
};
