<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use App\Models\Product;

class ClientMain extends Component
{
    use WithPagination;

    public $search = '';
    public $id;
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;
    public $selectedProducts = [];

    // Reset page when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clientes = Client::with('products')
            ->where(function($query) {
                $query->where('nombre', 'LIKE', '%' . $this->search . '%')
                      ->orWhere('email', 'LIKE', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $allProducts = Product::all();

        return view('livewire.client-main', compact('clientes', 'allProducts'));
    }

    public function create()
    {
        $this->reset('id', 'nombre', 'email', 'telefono', 'direccion', 'selectedProducts');
        $this->resetErrorBag();
        $this->modal('showform')->show();
    }

    public function edit(Client $client)
    {
        $this->resetErrorBag();
        $this->id = $client->id;
        $this->nombre = $client->nombre;
        $this->email = $client->email;
        $this->telefono = $client->telefono;
        $this->direccion = $client->direccion;
        $this->selectedProducts = $client->products->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $this->modal('showform')->show();
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . ($this->id ?: 'NULL'),
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
        ]);

        if (!$this->id) {
            $client = Client::create([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
            ]);
        } else {
            $client = Client::find($this->id);
            $client->update([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
            ]);
        }

        $client->products()->sync($this->selectedProducts);

        $this->reset('id', 'nombre', 'email', 'telefono', 'direccion', 'selectedProducts');

        $this->modal('showform')->close();
    }

    public function delete($id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
        }
    }
}
