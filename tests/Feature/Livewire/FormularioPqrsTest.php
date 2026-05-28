<?php

namespace Tests\Feature\Livewire;

use App\Livewire\FormularioPqrs;
use App\Models\Pqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class FormularioPqrsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_pqrs_with_correct_radicado_format(): void
    {
        Notification::fake();

        Livewire::test(FormularioPqrs::class)
            ->set('nombre_ciudadano', 'Juan Pérez')
            ->set('numero_cedula', '12345678')
            ->set('email', 'juan@test.com')
            ->set('tipo_solicitud', 'queja')
            ->set('descripcion', 'La luminaria en la calle 5 no funciona desde hace 3 días')
            ->call('enviar')
            ->assertSet('paso', 3)
            ->assertSet('radicadoGenerado', function ($value) {
                return str_starts_with($value, 'PQRS-');
            });

        $this->assertSame(1, Pqrs::count());
    }

    public function test_step_one_validation_requires_nombre_and_cedula(): void
    {
        Livewire::test(FormularioPqrs::class)
            ->call('siguiente')
            ->assertHasErrors(['nombre_ciudadano', 'numero_cedula']);
    }

    public function test_step_two_validation_requires_tipo_and_descripcion(): void
    {
        Livewire::test(FormularioPqrs::class)
            ->set('paso', 2)
            ->call('enviar')
            ->assertHasErrors(['tipo_solicitud', 'descripcion']);
    }

    public function test_siguiente_advances_step_when_valid(): void
    {
        Livewire::test(FormularioPqrs::class)
            ->set('nombre_ciudadano', 'Maria Lopez')
            ->set('numero_cedula', '87654321')
            ->call('siguiente')
            ->assertSet('paso', 2);
    }

    public function test_anterior_goes_back(): void
    {
        Livewire::test(FormularioPqrs::class)
            ->set('paso', 2)
            ->call('anterior')
            ->assertSet('paso', 1);
    }

    public function test_pqrs_route_is_accessible(): void
    {
        $response = $this->withoutVite()->get('/pqrs');
        $response->assertStatus(200);
    }
}
