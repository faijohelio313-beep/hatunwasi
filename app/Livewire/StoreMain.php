<?php

namespace App\Livewire;

use App\Models\Combo;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class StoreMain extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedCategory = 'todos';
    public array  $cart = [];

    public ?int $selectedComboId = null;
    public bool $showDetailModal   = false;
    public bool $showCartDrawer    = false;
    public bool $showCheckoutForm  = false;
    public bool $checkoutSuccess   = false;
    public ?int $checkoutOrderId   = null;

    // Datos del cliente para el pedido
    public string $customerName  = '';
    public string $customerPhone = '';
    public string $customerEmail = '';

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    public function render()
    {
        $query = Combo::with('products');

        if ($this->selectedCategory !== 'todos') {
            $query->where('categoria', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$this->search}%")
                  ->orWhereHas('products', function ($pq) {
                      $pq->where('nombre', 'LIKE', "%{$this->search}%")
                         ->orWhere('codigo', 'LIKE', "%{$this->search}%");
                  });
            });
        }

        $combos = $query->paginate(12);

        $selectedCombo = $this->selectedComboId
            ? Combo::with('products')->find($this->selectedComboId)
            : null;

        return view('livewire.store-main', [
            'combos'        => $combos,
            'selectedCombo' => $selectedCombo,
            'cartItems'     => $this->getBatchedCartItems(),
            'cartTotal'     => $this->getCartTotal(),
            'cartCount'     => $this->getCartCount(),
        ])->layout('layouts.store');
    }

    // -------------------------------------------------------------------------
    // Filtros
    // -------------------------------------------------------------------------

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    // -------------------------------------------------------------------------
    // Carrito
    // -------------------------------------------------------------------------

    public function addToCart(int $comboId): void
    {
        $this->cart[$comboId] = ($this->cart[$comboId] ?? 0) + 1;
        $this->saveCart();
        $this->showCartDrawer = true;
        $this->dispatch('notify', ['message' => 'Combo añadido al carrito', 'type' => 'success']);
    }

    public function removeFromCart(int $comboId): void
    {
        unset($this->cart[$comboId]);
        $this->saveCart();
        $this->dispatch('notify', ['message' => 'Combo eliminado del carrito', 'type' => 'info']);
    }

    public function updateQuantity(int $comboId, int $quantity): void
    {
        if ($quantity <= 0) {
            unset($this->cart[$comboId]);
        } else {
            $this->cart[$comboId] = $quantity;
        }
        $this->saveCart();
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->saveCart();
    }

    public function getCartCount(): int
    {
        return array_sum($this->cart);
    }

    public function getCartTotal(): float
    {
        return collect($this->getBatchedCartItems())->sum('subtotal');
    }

    // -------------------------------------------------------------------------
    // Modal de detalle
    // -------------------------------------------------------------------------

    public function openDetail(int $comboId): void
    {
        $this->selectedComboId = $comboId;
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->selectedComboId = null;
        $this->showDetailModal = false;
    }

    // -------------------------------------------------------------------------
    // Checkout
    // -------------------------------------------------------------------------

    public function openCheckoutForm(): void
    {
        if (empty($this->cart)) return;
        $this->showCartDrawer    = false;
        $this->showCheckoutForm  = true;
    }

    public function confirmCheckout(): void
    {
        $this->validate([
            'customerName'  => 'required|string|min:3|max:100',
            'customerPhone' => 'required|string|min:6|max:20',
            'customerEmail' => 'nullable|email|max:150',
        ]);

        if (empty($this->cart)) return;

        $items = $this->getBatchedCartItems();
        $total = collect($items)->sum('subtotal');

        $order = Order::create([
            'customer_name'  => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'customer_email' => $this->customerEmail ?: null,
            'total'          => $total,
            'status'         => 'pendiente',
        ]);

        foreach ($items as $item) {
            $order->items()->create([
                'combo_id'       => $item['combo']->id,
                'combo_nombre'   => $item['combo']->nombre,
                'cantidad'       => $item['quantity'],
                'precio_unitario'=> $item['combo']->precio_oferta,
                'subtotal'       => $item['subtotal'],
            ]);
        }

        $this->clearCart();
        $this->showCheckoutForm = false;
        $this->checkoutSuccess  = true;
        $this->checkoutOrderId  = $order->id;

        $this->reset(['customerName', 'customerPhone', 'customerEmail']);
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function getBatchedCartItems(): array
    {
        if (empty($this->cart)) return [];

        $combos = Combo::whereIn('id', array_keys($this->cart))->get()->keyBy('id');
        $items  = [];

        foreach ($this->cart as $id => $qty) {
            if ($combo = $combos->get($id)) {
                $items[] = [
                    'combo'    => $combo,
                    'quantity' => $qty,
                    'subtotal' => $combo->precio_oferta * $qty,
                ];
            }
        }

        return $items;
    }

    private function saveCart(): void
    {
        session()->put('cart', $this->cart);
    }
}
