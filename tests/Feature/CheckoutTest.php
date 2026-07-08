<?php

use App\Livewire\StoreMain;
use App\Mail\OrderConfirmation;
use App\Models\Combo;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function crearComboConStock(int $stock = 10): Combo
{
    $combo = Combo::create([
        'nombre'        => 'Combo de Prueba',
        'descripcion'   => 'Combo para tests',
        'precio_lista'  => 100.00,
        'precio_oferta' => 80.00,
        'descuento'     => 20,
        'categoria'     => 'baño',
    ]);

    $producto = Product::create([
        'nombre'    => 'Producto de Prueba',
        'categoria' => 'baño',
        'precio'    => 50.00,
        'cantidad'  => $stock,
    ]);

    $combo->products()->attach($producto->id, ['tipo_uso' => 'pared']);

    return $combo;
}

it('crea el pedido con sus items y descuenta el stock', function () {
    Mail::fake();
    $combo = crearComboConStock(10);

    Livewire::test(StoreMain::class)
        ->set('cart', [$combo->id => 2])
        ->set('customerName', 'Cliente de Prueba')
        ->set('customerPhone', '987654321')
        ->set('customerEmail', 'cliente@test.com')
        ->set('paymentMethod', 'yape')
        ->call('confirmCheckout')
        ->assertHasNoErrors()
        ->assertSet('checkoutSuccess', true);

    // Pedido y detalle registrados
    expect(Order::count())->toBe(1);
    $order = Order::first();
    expect($order->customer_name)->toBe('Cliente de Prueba')
        ->and($order->status)->toBe('pendiente')
        ->and($order->metodo_pago)->toBe('yape')
        ->and((float) $order->total)->toBe(160.0)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->cantidad)->toBe(2);

    // Stock descontado: 10 - 2 = 8
    expect($combo->products->first()->fresh()->cantidad)->toBe(8);

    // Correo de confirmación enviado al cliente
    Mail::assertSent(OrderConfirmation::class, fn ($mail) => $mail->hasTo('cliente@test.com'));
});

it('bloquea el pedido cuando no hay stock suficiente', function () {
    Mail::fake();
    $combo = crearComboConStock(1); // solo 1 unidad disponible

    Livewire::test(StoreMain::class)
        ->set('cart', [$combo->id => 5]) // se piden 5
        ->set('customerName', 'Cliente de Prueba')
        ->set('customerPhone', '987654321')
        ->call('confirmCheckout')
        ->assertHasErrors('customerName');

    expect(Order::count())->toBe(0);
    expect($combo->products->first()->fresh()->cantidad)->toBe(1); // stock intacto
    Mail::assertNothingSent();
});

it('exige nombre y teléfono del cliente para confirmar', function () {
    $combo = crearComboConStock();

    Livewire::test(StoreMain::class)
        ->set('cart', [$combo->id => 1])
        ->set('customerName', '')
        ->set('customerPhone', '')
        ->call('confirmCheckout')
        ->assertHasErrors(['customerName', 'customerPhone']);

    expect(Order::count())->toBe(0);
});
