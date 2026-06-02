<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pqrs;
use App\Models\PqrsHistorial;
use App\Notifications\PqrsRadicadaNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ReporteController extends Controller
{
    /**
     * Recibe un reporte ciudadano desde la app de reporte y lo radica como PQRS.
     * Permite reportes identificados o anónimos (RETILAP 580.1).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'elemento_id'      => ['nullable', 'integer', 'exists:infraestructura_elementos,id'],
            'latitud'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'         => ['nullable', 'numeric', 'between:-180,180'],
            'tipo_solicitud'   => ['required', Rule::in(['peticion', 'queja', 'reclamo', 'solicitud'])],
            'descripcion'      => ['required', 'string', 'min:10', 'max:2000'],
            'nombre_ciudadano' => ['nullable', 'string', 'max:150'],
            'numero_cedula'    => ['nullable', 'string', 'max:20'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:150'],
        ]);

        $pqrs = DB::transaction(function () use ($data) {
            $pqrs = Pqrs::crearConRadicado([
                'numero_cedula'    => $data['numero_cedula'] ?? 'ANONIMO',
                'nombre_ciudadano' => $data['nombre_ciudadano'] ?? 'Ciudadano anónimo',
                'elemento_id'      => $data['elemento_id'] ?? null,
                'latitud'          => $data['latitud'] ?? null,
                'longitud'         => $data['longitud'] ?? null,
                'tipo_solicitud'   => $data['tipo_solicitud'],
                'descripcion'      => $data['descripcion'],
                'email'            => $data['email'] ?? null,
                'telefono'         => $data['telefono'] ?? null,
                'estado'           => 'radicada',
            ]);

            PqrsHistorial::create([
                'pqrs_id'         => $pqrs->id,
                'estado_anterior' => null,
                'estado_nuevo'    => 'radicada',
                'observacion'     => 'PQRS radicada por ciudadano (app de reporte)',
            ]);

            return $pqrs;
        });

        // La notificación no debe bloquear la respuesta al ciudadano.
        try {
            $pqrs->notify(new PqrsRadicadaNotification($pqrs));
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar la notificación del reporte: ' . $e->getMessage());
        }

        return response()->json([
            'radicado' => $pqrs->radicado,
            'estado'   => $pqrs->estado,
            'mensaje'  => 'Su reporte fue radicado exitosamente.',
        ], 201);
    }
}
