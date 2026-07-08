<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\DashboardController;
use App\Livewire\ComboMain;
use App\Livewire\OrderAdmin;
use App\Livewire\ProductAdmin;
use App\Livewire\StoreMain;
use Illuminate\Support\Facades\Route;

// Página pública de la tienda
Route::get('/', fn() => redirect()->route('store'))->name('home');
Route::get('/tienda', StoreMain::class)->name('store');

// Catálogos de los demás módulos de la empresa (solo vista, sin compra)
Route::get('/catalogo/{categoria}', [CatalogoController::class, 'show'])
    ->name('catalogo.proximamente');

// Panel de administración — requiere login
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/combos', ComboMain::class)->name('combos');
    Route::get('/productos', ProductAdmin::class)->name('productos');
    Route::get('/pedidos', OrderAdmin::class)->name('orders');
    Route::get('/', DashboardController::class)->name('dashboard');
});

// Dashboard de usuario autenticado
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__ . '/settings.php';
