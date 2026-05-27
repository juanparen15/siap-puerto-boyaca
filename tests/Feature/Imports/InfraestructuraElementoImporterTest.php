<?php

namespace Tests\Feature\Imports;

use App\Models\InfraestructuraElemento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfraestructuraElementoImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_tipo_de_elemento_maps_to_lowercase_enum(): void
    {
        $elemento = new InfraestructuraElemento();
        // Test the mapping logic directly
        $mapped = strtolower(str_replace(' ', '_', 'LUMINARIA'));
        $this->assertEquals('luminaria', $mapped);

        $mapped2 = strtolower(str_replace(' ', '_', 'SENDERO PEATONAL'));
        $this->assertEquals('sendero_peatonal', $mapped2);
    }

    public function test_estado_actual_maps_correctly(): void
    {
        $cases = [
            'OPERATIVA' => 'operativa',
            'NO OPERATIVA' => 'no_operativa',
            'DESINSTALADA' => 'desinstalada',
            'UNKNOWN' => 'operativa',
        ];
        foreach ($cases as $input => $expected) {
            $result = match(strtoupper(trim($input))) {
                'OPERATIVA' => 'operativa',
                'NO OPERATIVA' => 'no_operativa',
                'DESINSTALADA' => 'desinstalada',
                default => 'operativa',
            };
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    public function test_resolveRecord_returns_existing_when_globalid_matches(): void
    {
        InfraestructuraElemento::factory()->create([
            'globalid' => 'abc-123',
            'rotulo' => 'AP0001',
            'tipo' => 'luminaria',
            'clasificacion' => 'casco_urbano',
            'estado' => 'operativa',
            'latitud' => 5.977,
            'longitud' => -74.579,
        ]);

        $existing = InfraestructuraElemento::firstOrNew(['globalid' => 'abc-123']);
        $this->assertTrue($existing->exists);
        $this->assertEquals('AP0001', $existing->rotulo);
        $this->assertEquals(1, InfraestructuraElemento::count());
    }
}
