<?php

namespace Tests\Feature\Api;

use App\Models\InfraestructuraElemento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_elementos_returns_json_array(): void
    {
        InfraestructuraElemento::factory()->count(3)->create([
            'latitud' => 5.977,
            'longitud' => -74.579,
            'estado' => 'operativa',
        ]);

        $response = $this->getJson('/api/mapa/elementos');
        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_bounding_box_filter_works(): void
    {
        // Element inside Puerto Boyacá
        InfraestructuraElemento::factory()->create([
            'latitud' => 5.977,
            'longitud' => -74.579,
        ]);
        // Element far away (Bogotá)
        InfraestructuraElemento::factory()->create([
            'latitud' => 4.710,
            'longitud' => -74.072,
        ]);

        $response = $this->getJson('/api/mapa/elementos?sw_lat=5.9&sw_lng=-74.7&ne_lat=6.1&ne_lng=-74.4');
        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_tipo_filter_works(): void
    {
        InfraestructuraElemento::factory()->create(['tipo' => 'luminaria', 'latitud' => 5.977, 'longitud' => -74.579]);
        InfraestructuraElemento::factory()->create(['tipo' => 'poste', 'latitud' => 5.977, 'longitud' => -74.579]);

        $response = $this->getJson('/api/mapa/elementos?tipo=luminaria');
        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_filtra_por_estado(): void
    {
        InfraestructuraElemento::factory()->create(['estado' => 'operativa', 'latitud' => 5.977, 'longitud' => -74.579]);
        InfraestructuraElemento::factory()->create(['estado' => 'no_operativa', 'latitud' => 5.978, 'longitud' => -74.580]);

        $response = $this->getJson('/api/mapa/elementos?estado=operativa');

        $response->assertOk()
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['estado' => 'operativa']);
    }

    public function test_filtra_por_clasificacion(): void
    {
        InfraestructuraElemento::factory()->create(['clasificacion' => 'casco_urbano', 'latitud' => 5.977, 'longitud' => -74.579]);
        InfraestructuraElemento::factory()->create(['clasificacion' => 'puerto_serviez', 'latitud' => 5.978, 'longitud' => -74.580]);

        $response = $this->getJson('/api/mapa/elementos?clasificacion=casco_urbano');

        $response->assertOk()
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['clasificacion' => 'casco_urbano']);
    }
}
