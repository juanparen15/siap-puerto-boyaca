<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Plazos de respuesta (SLA) en días hábiles por tipo de solicitud
    |--------------------------------------------------------------------------
    | Basado en la normativa colombiana de PQRSD (Ley 1755 de 2015 / CPACA).
    | null = sin plazo legal (p. ej. felicitaciones).
    */
    'sla_dias_habiles' => [
        'peticion'     => 15,
        'queja'        => 15,
        'reclamo'      => 15,
        'sugerencia'   => 15,
        'denuncia'     => 15,
        'felicitacion' => null,
    ],

    // Umbral (en días hábiles restantes) para marcar el semáforo en ámbar.
    'umbral_alerta_dias' => 3,

    /*
    |--------------------------------------------------------------------------
    | Festivos nacionales de Colombia
    |--------------------------------------------------------------------------
    | Usados para el cálculo de días hábiles. VERIFICAR/ACTUALIZAR cada año.
    */
    'festivos' => [
        // 2026
        '2026-01-01', '2026-01-12', '2026-03-23', '2026-04-02', '2026-04-03',
        '2026-05-01', '2026-05-18', '2026-06-08', '2026-06-15', '2026-06-29',
        '2026-07-20', '2026-08-07', '2026-08-17', '2026-10-12', '2026-11-02',
        '2026-11-16', '2026-12-08', '2026-12-25',
    ],
];
