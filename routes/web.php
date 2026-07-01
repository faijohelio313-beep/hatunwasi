<?php

use App\Livewire\StoreMain;
use App\Livewire\ComboMain;
use App\Livewire\OrderAdmin;
use App\Models\Combo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Página pública de la tienda
Route::get('/', fn() => redirect()->route('store'))->name('home');
Route::get('/tienda', StoreMain::class)->name('store');

// Datos reales para el panel de resumen (dashboard)
$dashboardStats = function () {
    $totalPedidos  = Order::count();
    $pedidosValidos = Order::where('status', '!=', 'cancelado')->count();
    $ingresosTotal = Order::where('status', '!=', 'cancelado')->sum('total');

    return [
        'totalCombos'       => Combo::count(),
        'totalProductos'    => Product::count(),
        'totalUsuarios'     => User::count(),
        'totalPedidos'      => $totalPedidos,
        'pedidosPendientes' => Order::where('status', 'pendiente')->count(),
        'ingresosTotal'     => $ingresosTotal,
        'ticketPromedio'    => $pedidosValidos > 0 ? $ingresosTotal / $pedidosValidos : 0,

        'combosBano'    => Combo::where('categoria', 'baño')->count(),
        'combosCocina'  => Combo::where('categoria', 'cocina')->count(),
        'productosDisponibles' => Product::where('disponible', true)->count(),

        'pedidosPorEstado' => [
            'pendiente'  => Order::where('status', 'pendiente')->count(),
            'confirmado' => Order::where('status', 'confirmado')->count(),
            'entregado'  => Order::where('status', 'entregado')->count(),
            'cancelado'  => Order::where('status', 'cancelado')->count(),
        ],

        'topCombos' => OrderItem::selectRaw('combo_nombre, SUM(cantidad) as total_vendido')
            ->groupBy('combo_nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get(),

        'ultimosPedidos' => Order::latest()->take(5)->get(),
    ];
};

// Panel de administración — requiere login
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () use ($dashboardStats) {
    Route::get('/combos', ComboMain::class)->name('combos');
    Route::get('/pedidos', OrderAdmin::class)->name('orders');
    Route::get('/', fn() => view('dashboard', $dashboardStats()))->name('dashboard');
});

// Dashboard de usuario autenticado
Route::middleware(['auth', 'verified'])->group(function () use ($dashboardStats) {
    Route::get('dashboard', fn() => view('dashboard', $dashboardStats()))->name('dashboard');
});

require __DIR__ . '/settings.php';
