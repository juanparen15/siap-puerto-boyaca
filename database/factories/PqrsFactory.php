<?php

namespace Database\Factories;

use App\Models\Pqrs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pqrs>
 */
class PqrsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = now()->year;
        $seq = str_pad(fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);

        return [
            'radicado'         => 'PQRS-' . $year . '-' . $seq,
            'numero_cedula'    => fake()->numerify('##########'),
            'elemento_id'      => null,
            'latitud'          => null,
            'longitud'         => null,
            'tipo_solicitud'   => fake()->randomElement(['peticion', 'queja', 'reclamo', 'solicitud']),
            'descripcion'      => fake()->paragraph(),
            'nombre_ciudadano' => fake()->name(),
            'email'            => fake()->safeEmail(),
            'telefono'         => fake()->numerify('##########'),
            'estado'           => fake()->randomElement(['radicada', 'en_proceso', 'respondida', 'cerrada']),
            'accion_tomada'    => null,
            'fecha_respuesta'  => null,
            'funcionario_id'   => null,
        ];
    }
}
