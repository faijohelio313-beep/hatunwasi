<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Panel de Control: Pedidos</h2>
            <p class="text-xs text-gray-500 mt-1">Gestiona y actualiza el estado de los pedidos recibidos.</p>
        </div>
        <a href="{{ route('admin.combos') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded text-sm transition">
            ← Volver a Combos
        </a>
    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-yellow-600">{{ $totales['pendiente'] }}</span>
            <p class="text-xs font-bold text-yellow-700 uppercase mt-1">Pendientes</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-blue-600">{{ $totales['confirmado'] }}</span>
            <p class="text-xs font-bold text-blue-700 uppercase mt-1">Confirmados</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-green-600">{{ $totales['entregado'] }}</span>
            <p class="text-xs font-bold text-green-700 uppercase mt-1">Entregados</p>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, teléfono o ID..."
                class="w-full pl-10 pr-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <select wire:model.live="filterStatus" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="entregado">Entregado</option>
            <option value="cancelado">Cancelado</option>
        </select>
    </div>

    <!-- TABLA DE PEDIDOS -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider border-b">
                        <th class="p-4"># Pedido</th>
                        <th class="p-4">Cliente</th>
                        <th class="p-4">Contacto</th>
                        <th class="p-4 text-center">Combos</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-center">Estado</th>
                        <th class="p-4 text-center">Fecha</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($orders as $order)
                        @php
                            $colors = [
                                'pendiente'  => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                'confirmado' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                'entregado'  => 'bg-green-100 text-green-800 border border-green-200',
                                'cancelado'  => 'bg-red-100 text-red-800 border border-red-200',
                            ];
                            $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="p-4 font-mono font-bold text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4 font-bold">{{ $order->customer_name }}</td>
                            <td class="p-4 text-gray-500">
                                <div>📱 {{ $order->customer_phone }}</div>
                                @if($order->customer_email)<div class="text-xs">✉ {{ $order->customer_email }}</div>@endif
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 text-gray-700 font-bold px-2.5 py-0.5 rounded-full text-xs">{{ $order->items->count() }} combos</span>
                            </td>
                            <td class="p-4 text-right font-black text-orange-600">S/. {{ number_format($order->total, 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="text-xs font-extrabold px-2.5 py-1 rounded-full {{ $color }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="p-4 text-center text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-center space-x-1">
                                <button wire:click="viewDetail({{ $order->id }})" class="text-blue-600 hover:text-blue-800 font-semibold text-xs uppercase">Ver</button>
                                @if($order->status === 'pendiente')
                                    <button wire:click="updateStatus({{ $order->id }}, 'confirmado')" class="text-green-600 hover:text-green-800 font-semibold text-xs uppercase">Confirmar</button>
                                @elseif($order->status === 'confirmado')
                                    <button wire:click="updateStatus({{ $order->id }}, 'entregado')" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs uppercase">Entregar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-400">No hay pedidos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $orders->links() }}</div>
    </div>

    <!-- MODAL DETALLE PEDIDO -->
    @if($showDetailModal && $selectedOrder)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-xl w-full overflow-hidden">
                <div class="bg-gray-900 text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">Pedido #{{ str_pad($selectedOrder->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    <button wire:click="closeDetail" class="text-white hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Datos del cliente -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-1">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Datos del Cliente</h4>
                        <p class="text-sm font-bold text-gray-900">{{ $selectedOrder->customer_name }}</p>
                        <p class="text-sm text-gray-600">📱 {{ $selectedOrder->customer_phone }}</p>
                        @if($selectedOrder->customer_email)<p class="text-sm text-gray-600">✉ {{ $selectedOrder->customer_email }}</p>@endif
                        <p class="text-xs text-gray-400 mt-2">Registrado: {{ $selectedOrder->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
                    </div>

                    <!-- Combos del pedido -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Combos Pedidos</h4>
                        <div class="space-y-2">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex justify-between items-center bg-white border rounded-lg p-3">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">{{ $item->combo_nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->cantidad }} × S/. {{ number_format($item->precio_unitario, 2) }}</p>
                                    </div>
                                    <span class="font-black text-gray-900">S/. {{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t mt-3 pt-3 flex justify-between font-black text-gray-900 text-lg">
                            <span>Total</span>
                            <span class="text-orange-600">S/. {{ number_format($selectedOrder->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Cambiar estado -->
                    <div class="border-t pt-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Actualizar Estado</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['pendiente'=>'Pendiente','confirmado'=>'Confirmado','entregado'=>'Entregado','cancelado'=>'Cancelado'] as $status=>$label)
                                <button
                                    wire:click="updateStatus({{ $selectedOrder->id }}, '{{ $status }}')"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $selectedOrder->status === $status ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
