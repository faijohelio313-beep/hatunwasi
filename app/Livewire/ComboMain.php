<?php

namespace App\Livewire;

use App\Models\Combo;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ComboMain extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public ?int  $comboId = null;

    public string $nombre       = '';
    public ?string $descripcion = null;
    public float  $precio_oferta = 0.0;
    public float  $precio_lista  = 0.0;
    public int    $descuento     = 0;
    public string $categoria    = 'baño';
    public ?string $imagenActual = null;
    public $foto = null; // archivo temporal Livewire

    public array $selectedProducts = [];
    public array $productRoles     = [];

    public bool $showFormModal = false;

    protected function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'precio_oferta' => 'required|numeric|min:0',
            'precio_lista'  => 'required|numeric|min:0',
            'descuento'     => 'required|integer|min:0|max:100',
            'categoria'     => 'required|in:baño,cocina',
            'foto'          => 'nullable|image|max:3072', // max 3 MB
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $combos = Combo::with('products')
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('nombre', 'LIKE', "%{$this->search}%")
                          ->orWhere('categoria', 'LIKE', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        $allProducts = Product::orderBy('nombre')->get();

        return view('livewire.combo-main', [
            'combos'      => $combos,
            'allProducts' => $allProducts,
        ])->layout('layouts.app');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function edit(Combo $combo): void
    {
        $this->resetErrorBag();
        $this->comboId       = $combo->id;
        $this->nombre        = $combo->nombre;
        $this->descripcion   = $combo->descripcion;
        $this->precio_oferta = $combo->precio_oferta;
        $this->precio_lista  = $combo->precio_lista;
        $this->descuento     = $combo->descuento;
        $this->categoria     = $combo->categoria;
        $this->imagenActual  = $combo->imagen;
        $this->foto          = null;

        $this->selectedProducts = $combo->products
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->productRoles = [];
        foreach ($combo->products as $p) {
            $this->productRoles[$p->id] = $p->pivot->tipo_uso;
        }

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $imagenNombre = $this->imagenActual;

        if ($this->foto) {
            $dir = public_path('images/combos');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $ext          = $this->foto->getClientOriginalExtension();
            $imagenNombre = 'combo_' . ($this->comboId ?? 'new') . '_' . time() . '.' . $ext;
            $this->foto->move($dir, $imagenNombre);
        }

        $datos = [
            'nombre'        => $this->nombre,
            'descripcion'   => $this->descripcion,
            'precio_oferta' => $this->precio_oferta,
            'precio_lista'  => $this->precio_lista,
            'descuento'     => $this->descuento,
            'categoria'     => $this->categoria,
            'imagen'        => $imagenNombre,
        ];

        if ($this->comboId) {
            $combo = Combo::findOrFail($this->comboId);
            $combo->update($datos);
        } else {
            $combo = Combo::create($datos);
        }

        $syncData = [];
        foreach ($this->selectedProducts as $pId) {
            $syncData[$pId] = ['tipo_uso' => $this->productRoles[$pId] ?? 'general'];
        }
        $combo->products()->sync($syncData);

        $this->showFormModal = false;
        $this->resetForm();
        $this->dispatch('notify', ['message' => 'Combo guardado correctamente', 'type' => 'success']);
    }

    public function delete(int $id): void
    {
        $combo = Combo::find($id);
        if ($combo) {
            $combo->delete();
            $this->dispatch('notify', ['message' => 'Combo eliminado', 'type' => 'warning']);
        }
    }

    public function updatedPrecioLista(): void
    {
        $this->calculateDiscount();
    }

    public function updatedPrecioOferta(): void
    {
        $this->calculateDiscount();
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function calculateDiscount(): void
    {
        $lista  = (float) $this->precio_lista;
        $oferta = (float) $this->precio_oferta;

        $this->descuento = ($lista > 0 && $oferta > 0 && $lista >= $oferta)
            ? (int) round((($lista - $oferta) / $lista) * 100)
            : 0;
    }

    private function resetForm(): void
    {
        $this->reset([
            'comboId', 'nombre', 'descripcion', 'precio_oferta', 'precio_lista',
            'descuento', 'categoria', 'imagenActual', 'foto',
            'selectedProducts', 'productRoles',
        ]);
        $this->resetErrorBag();
    }
}
