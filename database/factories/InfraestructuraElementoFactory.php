<?php

namespace Database\Factories;

use App\Models\InfraestructuraElemento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfraestructuraElemento>
 */
class InfraestructuraElementoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo' => 'luminaria',
            'clasificacion' => 'casco_urbano',
            'estado' => 'operativa',
            'latitud' => fake()->latitude(-4, 13),
            'longitud' => fake()->longitude(-82, -66),
            'globalid' => \Illuminate\Support\Str::uuid(),
        ];
    }
}
