<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class DiasHabiles
{
    /** @return string[] Fechas de festivos (Y-m-d). */
    public static function festivos(): array
    {
        return config('pqrs.festivos', []);
    }

    public static function esHabil(CarbonInterface $fecha): bool
    {
        return ! $fecha->isWeekend()
            && ! in_array($fecha->format('Y-m-d'), static::festivos(), true);
    }

    /** Suma N días hábiles a una fecha. */
    public static function sumar(CarbonInterface $desde, int $dias): Carbon
    {
        $fecha = Carbon::instance($desde)->copy()->startOfDay();
        while ($dias > 0) {
            $fecha->addDay();
            if (static::esHabil($fecha)) {
                $dias--;
            }
        }
        return $fecha;
    }

    /**
     * Días hábiles entre dos fechas (con signo).
     * Positivo si $hasta es posterior a $desde; negativo si ya pasó.
     */
    public static function entre(CarbonInterface $desde, CarbonInterface $hasta): int
    {
        $a = Carbon::instance($desde)->copy()->startOfDay();
        $b = Carbon::instance($hasta)->copy()->startOfDay();
        if ($a->equalTo($b)) {
            return 0;
        }

        $signo = $a->lessThan($b) ? 1 : -1;
        [$inicio, $fin] = $signo === 1 ? [$a, $b] : [$b, $a];

        $dias = 0;
        $cursor = $inicio->copy();
        while ($cursor->lessThan($fin)) {
            $cursor->addDay();
            if (static::esHabil($cursor)) {
                $dias++;
            }
        }
        return $signo * $dias;
    }
}
