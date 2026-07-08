<?php

use App\Models\Combo;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Hatun Wasi: Módulo Combos
|--------------------------------------------------------------------------
| Endpoints públicos para que los demás módulos del grupo puedan consumir
| los datos de combos (sin autenticación — solo lectura).
|
| Base URL: http://localhost:8001/api/v1/
|--------------------------------------------------------------------------
*/

// throttle:60,1 → máximo 60 peticiones por minuto por IP, contra abuso de la API pública
Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    // GET /api/v1/combos
    // Lista todos los combos (con paginación opcional: ?per_page=12&page=1)
    Route::get('/combos', function (Request $request) {
        $perPage = min((int) $request->query('per_page', 12), 50);
        $categoria = $request->query('categoria'); // baño | cocina

        $combos = Combo::with('products')
            ->when($categoria, fn($q) => $q->where('categoria', $categoria))
            ->when($request->query('search'), function ($q) use ($request) {
                $s = $request->query('search');
                $q->where(function ($inner) use ($s) {
                    $inner->where('nombre', 'LIKE', "%$s%")
                          ->orWhere('descripcion', 'LIKE', "%$s%");
                });
            })
            ->paginate($perPage);

        return response()->json($combos);
    });

    // GET /api/v1/combos/{id}
    // Detalle de un combo con sus productos
    Route::get('/combos/{id}', function (int $id) {
        $combo = Combo::with('products')->find($id);

        if (!$combo) {
            return response()->json(['error' => 'Combo no encontrado'], 404);
        }

        return response()->json($combo);
    });

    // GET /api/v1/stats
    // Estadísticas generales del módulo (útil para el dashboard principal del grupo)
    Route::get('/stats', function () {
        return response()->json([
            'total_combos'       => Combo::count(),
            'combos_bano'        => Combo::where('categoria', 'baño')->count(),
            'combos_cocina'      => Combo::where('categoria', 'cocina')->count(),
            'total_pedidos'      => Order::count(),
            'pedidos_pendientes' => Order::where('status', 'pendiente')->count(),
            'ingresos_total'     => Order::where('status', '!=', 'cancelado')->sum('total'),
        ]);
    });

    // GET /api/v1/categorias
    // Lista de categorías disponibles
    Route::get('/categorias', function () {
        return response()->json([
            ['slug' => 'bano',   'label' => 'Baños',   'count' => Combo::where('categoria', 'baño')->count()],
            ['slug' => 'cocina', 'label' => 'Cocinas', 'count' => Combo::where('categoria', 'cocina')->count()],
        ]);
    });
});
