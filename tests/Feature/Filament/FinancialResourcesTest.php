<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialResourcesTest extends TestCase
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

    public function test_admin_can_access_facturacion_periodos_index(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/admin/facturacion-periodos')
            ->assertStatus(200);
    }

    public function test_admin_can_access_recaudos_index(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/admin/recaudos')
            ->assertStatus(200);
    }

    public function test_admin_can_access_financiamiento_recursos_index(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/admin/financiamiento-recursos')
            ->assertStatus(200);
    }

    public function test_admin_can_access_interventoria_informes_index(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/admin/interventoria-informes')
            ->assertStatus(200);
    }
}
