<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-5 rounded-xl">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl font-black text-neutral-800 dark:text-white">Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                <p class="text-xs text-neutral-400 mt-0.5">Resumen general de Hatun Wasi — {{ now()->translatedFormat('d \d\e F, Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.combos') }}" class="px-3 py-1.5 border rounded text-xs font-bold text-neutral-600 dark:text-neutral-300 border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-800">+ Crear Combo</a>
                <a href="{{ route('admin.orders') }}" class="px-3 py-1.5 bg-[#A98A4B] hover:bg-[#8a6f3a] text-white rounded text-xs font-bold">Ver Pedidos</a>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid auto-rows-min gap-4 grid-cols-2 md:grid-cols-4">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#A98A4B]/40">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Combos</span>
                <div class="text-3xl font-black text-neutral-800 dark:text-white mt-1">{{ $totalCombos }}</div>
                <span class="text-[10px] text-neutral-400">{{ $combosBano }} baño · {{ $combosCocina }} cocina</span>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#A98A4B]/40">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Productos</span>
                <div class="text-3xl font-black text-neutral-800 dark:text-white mt-1">{{ $totalProductos }}</div>
                <span class="text-[10px] text-neutral-400">{{ $productosDisponibles }} disponibles</span>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#A98A4B]/40">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Pedidos pendientes</span>
                <div class="text-3xl font-black text-amber-600 mt-1">{{ $pedidosPendientes }}</div>
                <span class="text-[10px] text-neutral-400">{{ $totalPedidos }} en total</span>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#A98A4B]/40">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">Ingresos</span>
                <div class="text-3xl font-black text-green-600 mt-1">S/. {{ number_format($ingresosTotal, 0) }}</div>
                <span class="text-[10px] text-neutral-400">Ticket prom. S/. {{ number_format($ticketPromedio, 2) }}</span>
            </div>
        </div>

        <!-- Segunda fila: categorías + estados -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Combos por categoría -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-4">Combos por categoría</h3>
                @php $totalCat = max($totalCombos, 1); @endphp
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-neutral-600 dark:text-neutral-300"><svg class="w-4 h-4 inline -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a7 7 0 007-7c0-4-7-11-7-11S5 10 5 14a7 7 0 007 7z"/></svg> Baño</span>
                            <span class="text-neutral-400">{{ $combosBano }}</span>
                        </div>
                        <div class="h-2 bg-neutral-100 dark:bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500" style="width: {{ ($combosBano / $totalCat) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-neutral-600 dark:text-neutral-300"><svg class="w-4 h-4 inline -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3s5 4.5 5 9a5 5 0 01-10 0c0-1.5.5-3 1.5-4.5 0 0 .5 2 2 2.5C10 8 10.5 5 12 3z"/></svg> Cocina</span>
                            <span class="text-neutral-400">{{ $combosCocina }}</span>
                        </div>
                        <div class="h-2 bg-neutral-100 dark:bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500" style="width: {{ ($combosCocina / $totalCat) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos por estado -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-4">Pedidos por estado</h3>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div>
                        <div class="text-xl font-black text-amber-600">{{ $pedidosPorEstado['pendiente'] }}</div>
                        <div class="text-[9px] uppercase text-neutral-400 font-bold">Pendiente</div>
                    </div>
                    <div>
                        <div class="text-xl font-black text-blue-600">{{ $pedidosPorEstado['confirmado'] }}</div>
                        <div class="text-[9px] uppercase text-neutral-400 font-bold">Confirmado</div>
                    </div>
                    <div>
                        <div class="text-xl font-black text-green-600">{{ $pedidosPorEstado['entregado'] }}</div>
                        <div class="text-[9px] uppercase text-neutral-400 font-bold">Entregado</div>
                    </div>
                    <div>
                        <div class="text-xl font-black text-red-500">{{ $pedidosPorEstado['cancelado'] }}</div>
                        <div class="text-[9px] uppercase text-neutral-400 font-bold">Cancelado</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila: top combos + últimos pedidos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">

            <!-- Top combos más vendidos -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-4">Combos más vendidos</h3>
                @if($topCombos->isEmpty())
                    <div class="flex flex-col items-center justify-center h-32 text-neutral-400 text-xs text-center">
                        Aún no hay ventas registradas.<br>Aparecerán aquí cuando se confirmen pedidos.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($topCombos as $i => $tc)
                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-[#A98A4B]/15 text-[#8a6f3a] text-[10px] font-black">{{ $i + 1 }}</span>
                                <span class="flex-1 text-xs text-neutral-700 dark:text-neutral-300 truncate">{{ $tc->combo_nombre }}</span>
                                <span class="text-xs font-bold text-neutral-500">{{ $tc->total_vendido }} vendidos</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Últimos pedidos -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-4">Últimos pedidos</h3>

                @if($ultimosPedidos->isEmpty())
                    <div class="flex flex-col items-center justify-center h-32 text-neutral-400 text-xs">
                        Todavía no hay pedidos registrados.
                    </div>
                @else
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($ultimosPedidos as $pedido)
                            <div class="flex items-center justify-between py-2.5 text-xs">
                                <div>
                                    <span class="font-bold text-neutral-800 dark:text-white">{{ $pedido->customer_name }}</span>
                                    <span class="text-neutral-400 ml-1">#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-neutral-600 dark:text-neutral-300">S/. {{ number_format($pedido->total, 2) }}</span>
                                    <span class="text-[9px] uppercase font-bold px-2 py-0.5 rounded
                                        {{ match($pedido->status) {
                                            'pendiente' => 'bg-amber-100 text-amber-700',
                                            'confirmado' => 'bg-blue-100 text-blue-700',
                                            'entregado' => 'bg-green-100 text-green-700',
                                            'cancelado' => 'bg-red-100 text-red-700',
                                            default => 'bg-neutral-100 text-neutral-700',
                                        } }}">
                                        {{ $pedido->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
