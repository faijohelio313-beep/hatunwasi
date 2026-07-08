<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-zinc-700 pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-zinc-100">Panel de Control: Pedidos</h2>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Gestiona y actualiza el estado de los pedidos recibidos.</p>
        </div>
        <a href="{{ route('admin.combos') }}" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-bold rounded text-sm transition">
            ← Volver a Combos
        </a>
    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-yellow-600 dark:text-yellow-400">{{ $totales['pendiente'] }}</span>
            <p class="text-xs font-bold text-yellow-700 dark:text-yellow-300 uppercase mt-1">Pendientes</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $totales['confirmado'] }}</span>
            <p class="text-xs font-bold text-blue-700 dark:text-blue-300 uppercase mt-1">Confirmados</p>
        </div>
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 text-center">
            <span class="text-3xl font-black text-green-600 dark:text-green-400">{{ $totales['entregado'] }}</span>
            <p class="text-xs font-bold text-green-700 dark:text-green-300 uppercase mt-1">Entregados</p>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, teléfono o ID..."
                class="w-full pl-10 pr-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200 placeholder-gray-400 dark:placeholder-zinc-500">
            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-zinc-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <select wire:model.live="filterStatus" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="entregado">Entregado</option>
            <option value="cancelado">Cancelado</option>
        </select>
    </div>

    <!-- TABLA DE PEDIDOS -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-zinc-900 text-xs font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider border-b border-gray-200 dark:border-zinc-700">
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
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-zinc-300">
                    @forelse($orders as $order)
                        @php
                            $colors = [
                                'pendiente'  => 'bg-yellow-100 text-yellow-800 border border-yellow-200 dark:bg-yellow-500/10 dark:text-yellow-300 dark:border-yellow-500/20',
                                'confirmado' => 'bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20',
                                'entregado'  => 'bg-green-100 text-green-800 border border-green-200 dark:bg-green-500/10 dark:text-green-300 dark:border-green-500/20',
                                'cancelado'  => 'bg-red-100 text-red-800 border border-red-200 dark:bg-red-500/10 dark:text-red-300 dark:border-red-500/20',
                            ];
                            $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition duration-150">
                            <td class="p-4 font-mono font-bold text-gray-900 dark:text-white">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4 font-bold text-gray-900 dark:text-zinc-100">{{ $order->customer_name }}</td>
                            <td class="p-4 text-gray-500 dark:text-zinc-400">
                                <div><svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>{{ $order->customer_phone }}</div>
                                @if($order->customer_email)<div class="text-xs"><svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>{{ $order->customer_email }}</div>@endif
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-bold px-2.5 py-0.5 rounded-full text-xs">{{ $order->items->count() }} combos</span>
                            </td>
                            <td class="p-4 text-right font-black text-[#A98A4B] dark:text-[#d8c39a]">S/. {{ number_format($order->total, 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="text-xs font-extrabold px-2.5 py-1 rounded-full {{ $color }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="p-4 text-center text-xs text-gray-400 dark:text-zinc-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-center space-x-1">
                                <button wire:click="viewDetail({{ $order->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs uppercase">Ver</button>
                                @if($order->status === 'pendiente')
                                    <button wire:click="updateStatus({{ $order->id }}, 'confirmado')" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-semibold text-xs uppercase">Confirmar</button>
                                @elseif($order->status === 'confirmado')
                                    <button wire:click="updateStatus({{ $order->id }}, 'entregado')" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold text-xs uppercase">Entregar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-400 dark:text-zinc-500">No hay pedidos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-zinc-700 [&_a]:dark:text-zinc-300 [&_span]:dark:text-zinc-500">{{ $orders->links() }}</div>
    </div>

    <!-- MODAL DETALLE PEDIDO -->
    @if($showDetailModal && $selectedOrder)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-2xl max-w-xl w-full overflow-hidden">
                <div class="bg-gray-900 dark:bg-zinc-950 text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">Pedido #{{ str_pad($selectedOrder->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    <button wire:click="closeDetail" aria-label="Cerrar detalle" class="text-white hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Datos del cliente -->
                    <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-4 border border-gray-200 dark:border-zinc-700 space-y-1">
                        <h4 class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-2">Datos del Cliente</h4>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $selectedOrder->customer_name }}</p>
                        <p class="text-sm text-gray-600 dark:text-zinc-300"><svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>{{ $selectedOrder->customer_phone }}</p>
                        @if($selectedOrder->customer_email)<p class="text-sm text-gray-600 dark:text-zinc-300"><svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>{{ $selectedOrder->customer_email }}</p>@endif
                        <p class="text-sm text-gray-600 dark:text-zinc-300"><svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>Pago: <span class="font-bold">{{ $selectedOrder->metodo_pago_label }}</span></p>
                        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-2">Registrado: {{ $selectedOrder->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
                    </div>

                    <!-- Combos del pedido -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-2">Combos Pedidos</h4>
                        <div class="space-y-2">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex justify-between items-center bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-3">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $item->combo_nombre }}</p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $item->cantidad }} × S/. {{ number_format($item->precio_unitario, 2) }}</p>
                                    </div>
                                    <span class="font-black text-gray-900 dark:text-white">S/. {{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-gray-200 dark:border-zinc-700 mt-3 pt-3 flex justify-between font-black text-gray-900 dark:text-white text-lg">
                            <span>Total</span>
                            <span class="text-[#A98A4B] dark:text-[#d8c39a]">S/. {{ number_format($selectedOrder->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Cambiar estado -->
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                        <h4 class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-3">Actualizar Estado</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['pendiente'=>'Pendiente','confirmado'=>'Confirmado','entregado'=>'Entregado','cancelado'=>'Cancelado'] as $status=>$label)
                                <button
                                    wire:click="updateStatus({{ $selectedOrder->id }}, '{{ $status }}')"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $selectedOrder->status === $status ? 'bg-[#A98A4B] text-white border-[#A98A4B]' : 'bg-white dark:bg-zinc-900 text-gray-700 dark:text-zinc-300 border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700' }}"
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
