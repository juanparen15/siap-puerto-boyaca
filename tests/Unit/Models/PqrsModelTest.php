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
        $radicado = Pqrs::generarRadicado();
        $this->assertMatchesRegularExpression('/^PQRS-\d{4}-\d{6}$/', $radicado);
    }
}
