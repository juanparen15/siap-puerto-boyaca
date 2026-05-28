<?php

namespace Tests\Unit\Models;

use App\Models\Pqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PqrsModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_radicado_with_correct_format(): void
    {
        $pqrs = \DB::transaction(fn () => Pqrs::crearConRadicado([
            'numero_cedula' => '12345678',
            'tipo_solicitud' => 'peticion',
            'descripcion' => 'Descripción de prueba para el test unitario',
            'nombre_ciudadano' => 'Test Usuario',
            'estado' => 'radicada',
        ]));

        $this->assertInstanceOf(Pqrs::class, $pqrs);
        $this->assertMatchesRegularExpression('/^PQRS-\d{4}-\d{6}$/', $pqrs->radicado);
    }
}
