<?php

namespace Tests\Feature\Filament;

use App\Models\Pqrs;
use App\Models\PqrsHistorial;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PqrsResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesSeeder::class);
    }

    private function adminUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        return $user;
    }

    public function test_admin_can_access_pqrs_index(): void
    {
        $user = $this->adminUser();

        $this->actingAs($user)
            ->get('/admin/pqrs')
            ->assertStatus(200);
    }

    public function test_cambiar_estado_action_changes_estado_and_creates_historial(): void
    {
        Notification::fake();

        $user = $this->adminUser();
        $pqrs = Pqrs::factory()->create(['estado' => 'radicada']);

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Filament\Resources\PqrsResource\Pages\ListPqrs::class)
            ->callTableAction('cambiarEstado', $pqrs, data: [
                'estado_nuevo'  => 'en_proceso',
                'observacion'   => 'Asignado al técnico.',
                'accion_tomada' => null,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertEquals('en_proceso', $pqrs->fresh()->estado);
        $this->assertNull($pqrs->fresh()->fecha_respuesta); // not a terminal state
        $this->assertSame(1, PqrsHistorial::where('pqrs_id', $pqrs->id)->count());
        $this->assertDatabaseHas('pqrs_historial', [
            'pqrs_id'         => $pqrs->id,
            'estado_anterior' => 'radicada',
            'estado_nuevo'    => 'en_proceso',
            'observacion'     => 'Asignado al técnico.',
        ]);
    }

    public function test_cambiar_estado_to_resuelta_sets_fecha_respuesta_and_historial(): void
    {
        Notification::fake();

        $user = $this->adminUser();
        $pqrs = Pqrs::factory()->create(['estado' => 'en_proceso', 'accion_tomada' => null]);

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Filament\Resources\PqrsResource\Pages\ListPqrs::class)
            ->callTableAction('cambiarEstado', $pqrs, data: [
                'estado_nuevo'  => 'resuelta',
                'observacion'   => 'Problema resuelto.',
                'accion_tomada' => 'Luminaria reemplazada.',
            ])
            ->assertHasNoTableActionErrors();

        $fresh = $pqrs->fresh();
        $this->assertEquals('resuelta', $fresh->estado);
        $this->assertEquals('Luminaria reemplazada.', $fresh->accion_tomada);
        $this->assertNotNull($fresh->fecha_respuesta); // terminal state → fecha_respuesta set
        $this->assertDatabaseHas('pqrs_historial', [
            'pqrs_id'         => $pqrs->id,
            'estado_anterior' => 'en_proceso',
            'estado_nuevo'    => 'resuelta',
        ]);
    }

    public function test_cambiar_estado_to_cerrada_sets_fecha_respuesta(): void
    {
        Notification::fake();

        $user = $this->adminUser();
        $pqrs = Pqrs::factory()->create(['estado' => 'resuelta']);

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Filament\Resources\PqrsResource\Pages\ListPqrs::class)
            ->callTableAction('cambiarEstado', $pqrs, data: [
                'estado_nuevo'  => 'cerrada',
                'observacion'   => 'Expediente cerrado.',
                'accion_tomada' => null,
            ])
            ->assertHasNoTableActionErrors();

        $fresh = $pqrs->fresh();
        $this->assertEquals('cerrada', $fresh->estado);
        $this->assertNotNull($fresh->fecha_respuesta); // terminal state → fecha_respuesta set
    }
}
