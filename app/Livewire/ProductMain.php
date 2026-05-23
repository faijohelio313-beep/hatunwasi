<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

use Livewire\Attributes\Validate;

class ProductMain extends Component
{
    public $search, $descripcion, $id;

    #[Validate('required')]
    public $nombre, $cantidad, $precio, $disponible = false;

    public function render(){
        $productos = Product::where('nombre', 'LIKE', '%'.$this->search.'%')
            ->latest()
            ->paginate();
        return view('livewire.product-main', compact('productos'));
    }

    public function create()
    {
        $this->reset('id', 'nombre', 'descripcion', 'cantidad', 'precio', 'disponible');
        $this->modal('showform')->show();
    }

    public function edit(Product $item)
    {
        $this->id = $item->id;
        $this->nombre = $item->nombre;
        $this->descripcion = $item->descripcion;
        $this->cantidad = $item->cantidad;
        $this->precio = $item->precio;
        $this->disponible = $item->disponible;

        $this->modal('showform')->show();
    }

    public function save()
    {
        $this->validate();

        if (!$this->id) {
            Product::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'cantidad' => $this->cantidad,
                'precio' => $this->precio,
                'disponible' => $this->disponible,
            ]);
        } else {
            $producto = Product::find($this->id);
            $producto->update([
                'id' => $this->id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'cantidad' => $this->cantidad,
                'precio' => $this->precio,
                'disponible' => $this->disponible,
            ]);
        }

        $this->reset('id', 'nombre', 'descripcion', 'cantidad', 'precio', 'disponible');

        $this->modal('showform')->close();
    }

    public function delete($id)
    {
        $producto = Product::find($id);
        if ($producto) {
            $producto->delete();
        }
    }
}
