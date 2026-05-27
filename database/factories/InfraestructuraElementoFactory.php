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
            'latitud' => 5.977,
            'longitud' => -74.579,
            'globalid' => \Illuminate\Support\Str::uuid(),
        ];
    }
}
