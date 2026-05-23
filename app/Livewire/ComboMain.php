<?php

namespace App\Livewire;

use App\Models\Combo;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ComboMain extends Component
{
    use WithPagination;

    public $search = '';
    public $comboId = null;
    public $nombre;
    public $descripcion;
    public $precio_oferta = 0.0;
    public $precio_lista = 0.0;
    public $descuento = 0;
    public $categoria = 'baño';
    public $selectedProducts = []; // Array de product_ids seleccionados
    public $productRoles = []; // Array de roles mapeados: [product_id => tipo_uso]

    public $showFormModal = false;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio_oferta' => 'required|numeric|min:0',
        'precio_lista' => 'required|numeric|min:0',
        'descuento' => 'required|integer|min:0|max:100',
        'categoria' => 'required|in:baño,cocina',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $combos = Combo::with('products')
            ->where('nombre', 'LIKE', '%' . $this->search . '%')
            ->orWhere('categoria', 'LIKE', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        // Cargar todos los productos disponibles para asignarlos
        $allProducts = Product::orderBy('nombre')->get();

        return view('livewire.combo-main', [
            'combos' => $combos,
            'allProducts' => $allProducts
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['comboId', 'nombre', 'descripcion', 'precio_oferta', 'precio_lista', 'descuento', 'categoria', 'selectedProducts', 'productRoles']);
        $this->resetErrorBag();
        $this->showFormModal = true;
    }

    public function edit(Combo $combo)
    {
        $this->resetErrorBag();
        $this->comboId = $combo->id;
        $this->nombre = $combo->nombre;
        $this->descripcion = $combo->descripcion;
        $this->precio_oferta = $combo->precio_oferta;
        $this->precio_lista = $combo->precio_lista;
        $this->descuento = $combo->descuento;
        $this->categoria = $combo->categoria;
        
        $this->selectedProducts = $combo->products->pluck('id')->map(fn($id) => (string)$id)->toArray();
        
        // Mapear los roles/usos de los productos asociados
        $this->productRoles = [];
        foreach ($combo->products as $p) {
            $this->productRoles[$p->id] = $p->pivot->tipo_uso;
        }

        $this->showFormModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->comboId) {
            $combo = Combo::find($this->comboId);
            $combo->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio_oferta' => $this->precio_oferta,
                'precio_lista' => $this->precio_lista,
                'descuento' => $this->descuento,
                'categoria' => $this->categoria,
            ]);
        } else {
            $idStr = str_pad(Combo::count() + 1, 2, '0', STR_PAD_LEFT);
            $combo = Combo::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio_oferta' => $this->precio_oferta,
                'precio_lista' => $this->precio_lista,
                'descuento' => $this->descuento,
                'categoria' => $this->categoria,
                'imagen' => "ambiente_{$idStr}.jpg"
            ]);
        }

        // Sincronizar productos y asociar sus tipo_uso correspondientes
        $syncData = [];
        foreach ($this->selectedProducts as $pId) {
            $tipoUso = $this->productRoles[$pId] ?? 'general';
            $syncData[$pId] = ['tipo_uso' => $tipoUso];
        }

        $combo->products()->sync($syncData);

        $this->showFormModal = false;
        $this->dispatch('notify', ['message' => 'Combo guardado correctamente', 'type' => 'success']);
    }

    public function delete($id)
    {
        $combo = Combo::find($id);
        if ($combo) {
            $combo->delete();
            $this->dispatch('notify', ['message' => 'Combo eliminado correctamente', 'type' => 'warning']);
        }
    }

    /**
     * Calcular descuento automáticamente si se cambian los precios.
     */
    public function updatedPrecioLista()
    {
        $this->calculateDiscount();
    }

    public function updatedPrecioOferta()
    {
        $this->calculateDiscount();
    }

    private function calculateDiscount()
    {
        $lista = (double)$this->precio_lista;
        $oferta = (double)$this->precio_oferta;

        if ($lista > 0 && $oferta > 0 && $lista >= $oferta) {
            $this->descuento = (int)round((($lista - $oferta) / $lista) * 100);
        } else {
            $this->descuento = 0;
        }
    }
}
