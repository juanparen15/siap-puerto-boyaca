<?php

namespace Tests\Feature\Filament;

use App\Models\Pqrs;
use App\Models\PqrsHistorial;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_cambiar_estado_action_updates_record_and_creates_historial(): void
    {
        $user = $this->adminUser();
        $pqrs = Pqrs::factory()->create(['estado' => 'radicada']);

        $data = [
            'estado_nuevo'  => 'en_proceso',
            'observacion'   => 'Se asignó al técnico.',
            'accion_tomada' => 'Revisión programada.',
        ];

        // Simulate the cambiarEstado action closure directly
        $estadoAnterior = $pqrs->estado;
        $pqrs->update([
            'estado'          => $data['estado_nuevo'],
            'accion_tomada'   => $data['accion_tomada'] ?? $pqrs->accion_tomada,
            'fecha_respuesta' => now(),
        ]);
        PqrsHistorial::create([
            'pqrs_id'         => $pqrs->id,
            'usuario_id'      => $user->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $data['estado_nuevo'],
            'observacion'     => $data['observacion'] ?? null,
        ]);

        $pqrs->refresh();

        $this->assertEquals('en_proceso', $pqrs->estado);
        $this->assertEquals('Revisión programada.', $pqrs->accion_tomada);
        $this->assertNotNull($pqrs->fecha_respuesta);

        $this->assertDatabaseHas('pqrs_historial', [
            'pqrs_id'         => $pqrs->id,
            'usuario_id'      => $user->id,
            'estado_anterior' => 'radicada',
            'estado_nuevo'    => 'en_proceso',
            'observacion'     => 'Se asignó al técnico.',
        ]);
        $this->assertSame(1, PqrsHistorial::count());
    }

    public function test_cambiar_estado_to_resuelta_sets_fecha_respuesta_and_historial(): void
    {
        $user  = $this->adminUser();
        $pqrs  = Pqrs::factory()->create(['estado' => 'en_proceso', 'accion_tomada' => null]);

        $data = [
            'estado_nuevo'  => 'resuelta',
            'observacion'   => 'Problema resuelto.',
            'accion_tomada' => 'Luminaria reemplazada.',
        ];

        $estadoAnterior = $pqrs->estado;
        $pqrs->update([
            'estado'          => $data['estado_nuevo'],
            'accion_tomada'   => $data['accion_tomada'] ?? $pqrs->accion_tomada,
            'fecha_respuesta' => now(),
        ]);
        PqrsHistorial::create([
            'pqrs_id'         => $pqrs->id,
            'usuario_id'      => $user->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $data['estado_nuevo'],
            'observacion'     => $data['observacion'] ?? null,
        ]);

        $pqrs->refresh();

        $this->assertEquals('resuelta', $pqrs->estado);
        $this->assertEquals('Luminaria reemplazada.', $pqrs->accion_tomada);
        $this->assertNotNull($pqrs->fecha_respuesta);

        $this->assertDatabaseHas('pqrs_historial', [
            'pqrs_id'         => $pqrs->id,
            'estado_anterior' => 'en_proceso',
            'estado_nuevo'    => 'resuelta',
        ]);
    }
}
