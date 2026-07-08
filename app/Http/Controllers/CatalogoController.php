<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogoController extends Controller
{
    /**
     * Catálogos de los demás módulos de la empresa (Revestimientos, Accesorios,
     * Sanitarios, Cerámicos y Componentes). Solo vista: sin carrito ni compra.
     */
    private const CATALOGOS = [
        'revestimientos' => [
            'nombre' => 'Revestimientos',
            'db'     => 'revestimientos',
            'sub'    => 'Cerámica y porcelanato nacional e importado para pisos y paredes.',
            'hero'   => 'hero-cocina.jpg',
        ],
        'accesorios' => [
            'nombre' => 'Accesorios',
            'db'     => 'accesorios',
            'sub'    => 'Complementos para la instalación y decoración de baños, cocinas y lavanderías.',
            'hero'   => 'hero.jpg',
        ],
        'sanitarios' => [
            'nombre' => 'Sanitarios',
            'db'     => 'sanitarios',
            'sub'    => 'Ambientes exhibidos en tienda: inodoros, lavatorios, grifería y combinaciones.',
            'hero'   => 'hero-bano.jpg',
        ],
        'ceramicos-y-componentes' => [
            'nombre' => 'Cerámicos y Componentes',
            'db'     => 'ceramicos-componentes',
            'sub'    => 'Tablones tipo madera, porcelanatos 60x60 y pegamentos para instalación.',
            'hero'   => 'hero-cocina.jpg',
        ],
    ];

    public function show(string $categoria)
    {
        abort_unless(array_key_exists($categoria, self::CATALOGOS), 404);

        $info = self::CATALOGOS[$categoria];

        return view('catalogo-categoria', [
            'nombre'    => $info['nombre'],
            'subtitulo' => $info['sub'],
            'heroImg'   => $info['hero'],
            'productos' => Product::where('categoria', $info['db'])
                ->orderBy('tipo_producto')->orderBy('nombre')
                ->get(),
        ]);
    }
}
