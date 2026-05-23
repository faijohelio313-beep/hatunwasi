<?php

namespace App\Livewire;

use App\Models\Combo;
use Livewire\Component;

class StoreMain extends Component
{
    public $search = '';
    public $selectedCategory = 'todos'; // todos, baño, cocina
    public $cart = [];
    public $selectedComboId = null;
    public $showDetailModal = false;
    public $showCartDrawer = false;
    public $checkoutSuccess = false;

    // Escucha eventos si es necesario
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function mount()
    {
        // Cargar el carrito desde la sesión
        $this->cart = session()->get('cart', []);
    }

    public function render()
    {
        // Consultar los combos aplicando filtros
        $query = Combo::with('products');

        if ($this->selectedCategory !== 'todos') {
            $query->where('categoria', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'LIKE', '%' . $this->search . '%')
                  ->orWhereHas('products', function ($pq) {
                      $pq->where('nombre', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('codigo', 'LIKE', '%' . $this->search . '%');
                  });
            });
        }

        $combos = $query->get();

        // Obtener el desglose de productos del combo seleccionado para el modal
        $selectedCombo = $this->selectedComboId ? Combo::with('products')->find($this->selectedComboId) : null;

        return view('livewire.store-main', [
            'combos' => $combos,
            'selectedCombo' => $selectedCombo,
            'cartItems' => $this->getCartItems(),
            'cartTotal' => $this->getCartTotal(),
            'cartCount' => $this->getCartCount(),
        ])->layout('layouts.app'); // Utilizar el layout por defecto
    }

    /**
     * Añadir combo al carrito.
     */
    public function addToCart($comboId)
    {
        if (isset($this->cart[$comboId])) {
            $this->cart[$comboId]++;
        } else {
            $this->cart[$comboId] = 1;
        }

        $this->saveCart();
        $this->showCartDrawer = true; // Abrir el carrito lateral automáticamente
        $this->dispatch('notify', ['message' => 'Combo añadido al carrito', 'type' => 'success']);
    }

    /**
     * Quitar combo o reducir cantidad en el carrito.
     */
    public function removeFromCart($comboId)
    {
        if (isset($this->cart[$comboId])) {
            unset($this->cart[$comboId]);
        }

        $this->saveCart();
        $this->dispatch('notify', ['message' => 'Combo eliminado del carrito', 'type' => 'info']);
    }

    /**
     * Actualizar la cantidad de un item en el carrito.
     */
    public function updateQuantity($comboId, $quantity)
    {
        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            unset($this->cart[$comboId]);
        } else {
            $this->cart[$comboId] = $quantity;
        }

        $this->saveCart();
    }

    /**
     * Limpiar el carrito de compras.
     */
    public function clearCart()
    {
        $this->cart = [];
        $this->saveCart();
    }

    /**
     * Abrir el modal de detalle para un combo específico.
     */
    public function openDetail($comboId)
    {
        $this->selectedComboId = $comboId;
        $this->showDetailModal = true;
    }

    /**
     * Cerrar el modal de detalle.
     */
    public function closeDetail()
    {
        $this->selectedComboId = null;
        $this->showDetailModal = false;
    }

    /**
     * Simular la compra.
     */
    public function checkout()
    {
        if (empty($this->cart)) {
            return;
        }

        $this->clearCart();
        $this->showCartDrawer = false;
        $this->checkoutSuccess = true;
    }

    /**
     * Obtener el conteo total de items en el carrito.
     */
    public function getCartCount()
    {
        return array_sum($this->cart);
    }

    /**
     * Obtener el costo total de los productos en el carrito.
     */
    public function getCartTotal()
    {
        $total = 0;
        foreach ($this->cart as $id => $qty) {
            $combo = Combo::find($id);
            if ($combo) {
                $total += $combo->precio_oferta * $qty;
            }
        }
        return $total;
    }

    /**
     * Obtener los objetos Combo del carrito.
     */
    private function getCartItems()
    {
        $items = [];
        foreach ($this->cart as $id => $qty) {
            $combo = Combo::find($id);
            if ($combo) {
                $items[] = [
                    'combo' => $combo,
                    'quantity' => $qty,
                    'subtotal' => $combo->precio_oferta * $qty
                ];
            }
        }
        return $items;
    }

    /**
     * Guardar el carrito actual en la sesión de Laravel.
     */
    private function saveCart()
    {
        session()->put('cart', $this->cart);
    }
}
