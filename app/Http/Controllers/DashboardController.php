<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Panel de resumen del administrador con métricas reales del negocio.
     */
    public function __invoke()
    {
        $totalPedidos   = Order::count();
        $pedidosValidos = Order::where('status', '!=', 'cancelado')->count();
        $ingresosTotal  = Order::where('status', '!=', 'cancelado')->sum('total');

        return view('dashboard', [
            'totalCombos'       => Combo::count(),
            'totalProductos'    => Product::count(),
            'totalUsuarios'     => User::count(),
            'totalPedidos'      => $totalPedidos,
            'pedidosPendientes' => Order::where('status', 'pendiente')->count(),
            'ingresosTotal'     => $ingresosTotal,
            'ticketPromedio'    => $pedidosValidos > 0 ? $ingresosTotal / $pedidosValidos : 0,

            'combosBano'           => Combo::where('categoria', 'baño')->count(),
            'combosCocina'         => Combo::where('categoria', 'cocina')->count(),
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
        ]);
    }
}
