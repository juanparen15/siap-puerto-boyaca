<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ConsultaPqrs;
use App\Models\Pqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConsultaPqrsTest extends TestCase
{
    use RefreshDatabase;

    public function test_finds_pqrs_by_radicado_without_exposing_pii(): void
    {
        $pqrs = Pqrs::factory()->create(['radicado' => 'PQRS-2026-000001']);

        Livewire::test(ConsultaPqrs::class)
            ->set('busqueda', 'PQRS-2026-000001')
            ->set('tipoBusqueda', 'radicado')
            ->call('consultar')
            ->assertSet('pqrs.radicado', 'PQRS-2026-000001')
            ->assertDontSeeHtml($pqrs->email)
            ->assertDontSeeHtml($pqrs->nombre_ciudadano)
            ->assertDontSeeHtml($pqrs->telefono)
            ->assertDontSeeHtml($pqrs->descripcion);
    }

    public function test_finds_pqrs_by_cedula_without_exposing_pii(): void
    {
        $pqrs = Pqrs::factory()->create(['numero_cedula' => '98765432']);

        Livewire::test(ConsultaPqrs::class)
            ->set('busqueda', '98765432')
            ->set('tipoBusqueda', 'cedula')
            ->call('consultar')
            ->assertSet('pqrs.radicado', $pqrs->radicado)
            ->assertDontSeeHtml($pqrs->email)
            ->assertDontSeeHtml($pqrs->nombre_ciudadano)
            ->assertDontSeeHtml($pqrs->telefono)
            ->assertDontSeeHtml($pqrs->descripcion);
    }

    public function test_shows_error_when_pqrs_not_found(): void
    {
        Livewire::test(ConsultaPqrs::class)
            ->set('busqueda', 'PQRS-9999-999999')
            ->set('tipoBusqueda', 'radicado')
            ->call('consultar')
            ->assertSet('pqrs', null)
            ->assertSet('error', 'No se encontró ningún PQRS con los datos ingresados.');
    }

    public function test_pqrs_consultar_route_is_accessible(): void
    {
        $response = $this->withoutVite()->get('/pqrs/consultar');
        $response->assertStatus(200);
    }
}
