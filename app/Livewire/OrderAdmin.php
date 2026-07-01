<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderAdmin extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $selectedOrder = null;
    public $showDetailModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with('items')
            ->when($this->search, fn($q) =>
                $q->where('customer_name', 'LIKE', "%{$this->search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$this->search}%")
                  ->orWhere('id', 'LIKE', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->latest();

        return view('livewire.order-admin', [
            'orders'  => $query->paginate(15),
            'totales' => [
                'pendiente'  => Order::where('status', 'pendiente')->count(),
                'confirmado' => Order::where('status', 'confirmado')->count(),
                'entregado'  => Order::where('status', 'entregado')->count(),
            ],
        ])->layout('layouts.app');
    }

    public function viewDetail(Order $order): void
    {
        $this->selectedOrder = $order->load('items');
        $this->showDetailModal = true;
    }

    public function updateStatus(Order $order, string $status): void
    {
        $validStatuses = ['pendiente', 'confirmado', 'entregado', 'cancelado'];
        if (!in_array($status, $validStatuses)) return;

        $order->update(['status' => $status]);
        if ($this->selectedOrder && $this->selectedOrder->id === $order->id) {
            $this->selectedOrder->status = $status;
        }
        $this->dispatch('notify', ['message' => 'Estado actualizado', 'type' => 'success']);
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
    }
}
