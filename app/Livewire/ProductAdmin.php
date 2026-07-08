<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductAdmin extends Component
{
    use WithPagination;

    /** Categorías disponibles: módulo Combos + catálogos de la empresa */
    public const CATEGORIAS = [
        'baño'                  => 'Baño (Combos)',
        'cocina'                => 'Cocina (Combos)',
        'revestimientos'        => 'Revestimientos',
        'accesorios'            => 'Accesorios',
        'sanitarios'            => 'Sanitarios',
        'ceramicos-componentes' => 'Cerámicos y Componentes',
    ];

    public string $search = '';
    public string $filterCategoria = '';

    public ?int $productId = null;

    public string  $nombre = '';
    public ?string $codigo = null;
    public ?string $marca = null;
    public ?string $formato = null;
    public ?string $color = null;
    public ?string $tipo_producto = null;
    public string  $categoria = 'baño';
    public ?string $descripcion = null;
    public int     $cantidad = 100;
    public float   $precio = 0.0;
    public bool    $disponible = true;

    public bool $showFormModal = false;

    protected function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:255',
            'codigo'        => 'nullable|string|max:50',
            'marca'         => 'nullable|string|max:100',
            'formato'       => 'nullable|string|max:100',
            'color'         => 'nullable|string|max:100',
            'tipo_producto' => 'nullable|string|max:100',
            'categoria'     => 'required|in:' . implode(',', array_keys(self::CATEGORIAS)),
            'descripcion'   => 'nullable|string',
            'cantidad'      => 'required|integer|min:0',
            'precio'        => 'required|numeric|min:0',
            'disponible'    => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategoria(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $productos = Product::query()
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('nombre', 'LIKE', "%{$this->search}%")
                          ->orWhere('codigo', 'LIKE', "%{$this->search}%")
                          ->orWhere('marca', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->filterCategoria, fn($q) => $q->where('categoria', $this->filterCategoria))
            ->orderByDesc('id')
            ->paginate(12);

        return view('livewire.product-admin', [
            'productos'  => $productos,
            'categorias' => self::CATEGORIAS,
        ])->layout('layouts.app');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function edit(Product $product): void
    {
        $this->resetErrorBag();
        $this->productId     = $product->id;
        $this->nombre        = $product->nombre;
        $this->codigo        = $product->codigo;
        $this->marca         = $product->marca;
        $this->formato       = $product->formato;
        $this->color         = $product->color;
        $this->tipo_producto = $product->tipo_producto;
        $this->categoria     = $product->categoria ?? 'baño';
        $this->descripcion   = $product->descripcion;
        $this->cantidad      = (int) $product->cantidad;
        $this->precio        = (float) $product->precio;
        $this->disponible    = (bool) $product->disponible;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $datos = $this->validate();

        if ($this->productId) {
            Product::findOrFail($this->productId)->update($datos);
            $mensaje = 'Producto actualizado correctamente';
        } else {
            Product::create($datos);
            $mensaje = 'Producto registrado correctamente';
        }

        $this->showFormModal = false;
        $this->resetForm();
        $this->dispatch('notify', ['message' => $mensaje, 'type' => 'success']);
    }

    public function delete(int $id): void
    {
        $product = Product::find($id);
        if (!$product) return;

        // Proteger la integridad de los combos: no borrar productos en uso
        if ($product->combos()->count() > 0) {
            $this->dispatch('notify', [
                'message' => "No se puede eliminar: este producto forma parte de {$product->combos()->count()} combo(s). Quítalo de los combos primero o márcalo como no disponible.",
                'type'    => 'error',
            ]);
            return;
        }

        $product->delete();
        $this->dispatch('notify', ['message' => 'Producto eliminado', 'type' => 'warning']);
    }

    /** Alterna disponible/no disponible directo desde la tabla */
    public function toggleDisponible(int $id): void
    {
        $product = Product::find($id);
        if (!$product) return;

        $product->update(['disponible' => ! $product->disponible]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'productId', 'nombre', 'codigo', 'marca', 'formato', 'color',
            'tipo_producto', 'categoria', 'descripcion', 'cantidad', 'precio', 'disponible',
        ]);
        $this->resetErrorBag();
    }
}
