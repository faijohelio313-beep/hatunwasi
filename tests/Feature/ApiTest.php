<?php

use App\Models\Combo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lista los combos en JSON con su estructura de paginación', function () {
    Combo::create([
        'nombre' => 'Combo API', 'precio_lista' => 100, 'precio_oferta' => 80,
        'descuento' => 20, 'categoria' => 'baño',
    ]);

    $this->getJson('/api/v1/combos')
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'per_page'])
        ->assertJsonFragment(['nombre' => 'Combo API']);
});

it('devuelve 404 para un combo inexistente', function () {
    $this->getJson('/api/v1/combos/999')
        ->assertNotFound()
        ->assertJson(['error' => 'Combo no encontrado']);
});

it('expone las estadísticas generales', function () {
    $this->getJson('/api/v1/stats')
        ->assertOk()
        ->assertJsonStructure([
            'total_combos', 'combos_bano', 'combos_cocina',
            'total_pedidos', 'pedidos_pendientes', 'ingresos_total',
        ]);
});
