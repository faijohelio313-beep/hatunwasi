<?php

namespace Database\Seeders;

use App\Models\Combo;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AmbientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/ambientes.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at: $jsonPath");
            return;
        }

        $ambientes = json_decode(File::get($jsonPath), true);
        
        foreach ($ambientes as $amb) {
            $comboProducts = [];
            $totalLista = 0;

            foreach ($amb['items'] as $item) {
                // Generar precio lógico
                $precio = $this->getPrecioLogico($item['tipo_producto'] ?? '', $item['tipo_uso'] ?? '');
                
                // Generar un nombre descriptivo
                $nombreProducto = $item['tipo_producto'] ? $item['tipo_producto'] : 'Producto';
                if (!empty($item['marca'])) {
                    $nombreProducto .= ' ' . $item['marca'];
                }
                if (!empty($item['diseño'])) {
                    $nombreProducto .= ' (' . $item['diseño'] . ')';
                }
                if (!empty($item['modelo'])) {
                    $nombreProducto .= ' ' . $item['modelo'];
                }

                // Buscar por código o nombre para evitar duplicar
                $query = Product::query();
                if (!empty($item['codigo'])) {
                    $query->where('codigo', $item['codigo']);
                } else {
                    $query->where('nombre', $nombreProducto);
                }
                
                $product = $query->first();

                if (!$product) {
                    $product = Product::create([
                        'nombre' => $nombreProducto,
                        'codigo' => $item['codigo'] ?? null,
                        'marca' => $item['marca'] ?? null,
                        'formato' => $item['formato'] ?? null,
                        'color' => $item['color'] ?? null,
                        'tipo_producto' => $item['tipo_producto'] ?? null,
                        'categoria' => $amb['categoria'],
                        'descripcion' => "Diseño: " . ($item['diseño'] ?? 'N/A') . ". Formato: " . ($item['formato'] ?? 'N/A') . ".",
                        'precio' => $precio,
                        'cantidad' => rand(50, 200),
                        'disponible' => true
                    ]);
                }

                $comboProducts[] = [
                    'id' => $product->id,
                    'tipo_uso' => $item['tipo_uso'] ?? 'general'
                ];
                
                $totalLista += $product->precio;
            }

            // Descuento aleatorio del combo (10% a 35%)
            $descuentosPosibles = [10, 15, 20, 25, 30, 35];
            $descuento = $descuentosPosibles[array_rand($descuentosPosibles)];
            
            $precioOferta = round($totalLista * (1 - $descuento / 100), 2);
            $totalLista = round($totalLista, 2);

            // Mapear imagen real del ambiente
            $id = $amb['id'];
            $idStr = str_pad($id, 2, '0', STR_PAD_LEFT);
            if ($id === 5 || $id === 6) {
                $imagenPath = "{$id}.jpg";
            } elseif ($id === 20) {
                $imagenPath = "21.png"; // Fallback para la imagen 20 faltante
            } else {
                $imagenPath = "{$id}.png";
            }

            $combo = Combo::create([
                'nombre' => "COMBO Watun Wasi: " . ($amb['nombre_serie'] ?: "Ambiente {$idStr}"),
                'descripcion' => "Todo lo necesario para renovar tu espacio con la " . ($amb['nombre_serie'] ?: "Serie {$idStr}") . ". Incluye revestimientos y sanitarios coordinados.",
                'precio_lista' => $totalLista,
                'precio_oferta' => $precioOferta,
                'descuento' => $descuento,
                'categoria' => $amb['categoria'],
                'imagen' => $imagenPath
            ]);

            foreach ($comboProducts as $cp) {
                $combo->products()->attach($cp['id'], ['tipo_uso' => $cp['tipo_uso']]);
            }
        }
    }

    /**
     * Genera un precio lógico basado en la categoría del producto.
     */
    private function getPrecioLogico(string $tipoProducto, string $tipoUso): float
    {
        $tipoProducto = strtolower($tipoProducto);
        $tipoUso = strtolower($tipoUso);

        if (strpos($tipoProducto, 'inodoro') !== false) {
            return rand(399, 699) + 0.90;
        }
        if (strpos($tipoProducto, 'urinario') !== false) {
            return rand(199, 349) + 0.90;
        }
        if (strpos($tipoProducto, 'lavatorio') !== false) {
            return rand(120, 250) + 0.90;
        }
        if (strpos($tipoProducto, 'juego de baño') !== false) {
            return rand(499, 899) + 0.90;
        }
        if (strpos($tipoProducto, 'repostero') !== false || strpos($tipoProducto, 'reposteros') !== false) {
            return rand(600, 1200) + 0.90;
        }
        if ($tipoUso === 'pared') {
            return rand(39, 69) + 0.90;
        }
        if ($tipoUso === 'piso') {
            return rand(45, 79) + 0.90;
        }
        if (strpos($tipoProducto, 'lápiz') !== false || strpos($tipoProducto, 'listelo') !== false) {
            return rand(15, 35) + 0.90;
        }
        if (strpos($tipoProducto, 'inserto') !== false) {
            return rand(25, 49) + 0.90;
        }
        
        return rand(29, 99) + 0.90;
    }
}
