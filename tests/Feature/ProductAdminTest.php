<?php

use App\Livewire\ProductAdmin;
use App\Models\Combo;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('el administrador puede registrar un producto nuevo', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ProductAdmin::class)
        ->call('create')
        ->set('nombre', 'Porcelanato Importado Nuevo')
        ->set('codigo', 'HW-NEW-001')
        ->set('marca', 'Proveedor Externo')
        ->set('categoria', 'ceramicos-componentes')
        ->set('precio', 89.90)
        ->set('cantidad', 50)
        ->call('save')
        ->assertHasNoErrors();

    expect(Product::where('codigo', 'HW-NEW-001')->exists())->toBeTrue();
});

it('no permite eliminar un producto que pertenece a un combo', function () {
    $this->actingAs(User::factory()->create());

    $combo = Combo::create([
        'nombre' => 'Combo Test', 'precio_lista' => 100, 'precio_oferta' => 80,
        'descuento' => 20, 'categoria' => 'baño',
    ]);
    $producto = Product::create(['nombre' => 'Producto en Combo', 'categoria' => 'baño', 'precio' => 50, 'cantidad' => 10]);
    $combo->products()->attach($producto->id, ['tipo_uso' => 'pared']);

    Livewire::test(ProductAdmin::class)->call('delete', $producto->id);

    expect(Product::find($producto->id))->not->toBeNull(); // sigue existiendo
});

it('valida los campos obligatorios del producto', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ProductAdmin::class)
        ->call('create')
        ->set('nombre', '')
        ->call('save')
        ->assertHasErrors(['nombre']);

    expect(Product::count())->toBe(0);
});
