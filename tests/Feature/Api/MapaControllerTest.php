<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapaControllerTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // tipo
    // -----------------------------------------------------------------------

    public function test_invalid_tipo_returns_422(): void
    {
        $response = $this->getJson('/api/mapa/elementos?tipo=invalid_type');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    public function test_valid_tipo_does_not_return_422(): void
    {
        $this->getJson('/api/mapa/elementos?tipo=luminaria')
             ->assertSuccessful();
    }

    // -----------------------------------------------------------------------
    // estado
    // -----------------------------------------------------------------------

    public function test_invalid_estado_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?estado=rota')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['estado']);
    }

    // -----------------------------------------------------------------------
    // clasificacion
    // -----------------------------------------------------------------------

    public function test_invalid_clasificacion_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?clasificacion=zona_rural')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['clasificacion']);
    }

    // -----------------------------------------------------------------------
    // Bounding box - coordinate bounds
    // -----------------------------------------------------------------------

    public function test_lat_out_of_range_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?sw_lat=-91&ne_lat=5&sw_lng=-75&ne_lng=-74')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['sw_lat']);
    }

    public function test_lng_out_of_range_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?sw_lat=4&ne_lat=5&sw_lng=-181&ne_lng=-74')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['sw_lng']);
    }

    public function test_non_numeric_lat_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?sw_lat=abc&ne_lat=5&sw_lng=-75&ne_lng=-74')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['sw_lat']);
    }

    // -----------------------------------------------------------------------
    // Bounding box - required_with pairing
    // -----------------------------------------------------------------------

    public function test_partial_bounding_box_missing_ne_lat_returns_422(): void
    {
        // sw_lat provided but ne_lat omitted => required_with violation
        $this->getJson('/api/mapa/elementos?sw_lat=4&sw_lng=-75&ne_lng=-74')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['ne_lat']);
    }

    public function test_partial_bounding_box_missing_sw_lng_returns_422(): void
    {
        $this->getJson('/api/mapa/elementos?sw_lat=4&ne_lat=5&ne_lng=-74')
             ->assertStatus(422)
             ->assertJsonValidationErrors(['sw_lng']);
    }

    public function test_complete_valid_bounding_box_is_accepted(): void
    {
        $this->getJson('/api/mapa/elementos?sw_lat=4&ne_lat=5&sw_lng=-75&ne_lng=-74')
             ->assertSuccessful();
    }

    // -----------------------------------------------------------------------
    // No filters - baseline
    // -----------------------------------------------------------------------

    public function test_no_filters_returns_200(): void
    {
        $this->getJson('/api/mapa/elementos')
             ->assertStatus(200)
             ->assertJsonIsArray();
    }
}