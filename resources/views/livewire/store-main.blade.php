<div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    <!-- BARRA DE NAVEGACIÓN SUPERIOR (Watun Wasi Header) -->
    <header class="bg-orange-600 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
            <!-- Logo -->
            <div class="flex items-center gap-2 cursor-pointer" wire:click="$set('selectedCategory', 'todos')">
                <span class="text-2xl font-black tracking-tight flex items-center">
                    <span class="bg-white text-orange-600 px-2 py-0.5 rounded font-black mr-1 shadow-inner">W</span>ATUN WASI
                </span>
            </div>

            <!-- Buscador -->
            <div class="flex-1 max-w-xl relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Busca combos de baños, cocinas, cerámicos o códigos..." 
                    class="w-full pl-10 pr-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 border-none shadow-sm"
                >
                <div class="absolute left-3.5 top-2.5 text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Carrito -->
            <button 
                wire:click="$set('showCartDrawer', true)" 
                class="relative p-2 rounded-full hover:bg-orange-700 transition duration-150 focus:outline-none"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-white text-orange-600 font-bold text-xs rounded-full h-5 w-5 flex items-center justify-center border-2 border-orange-600 shadow-sm animate-pulse">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </header>

    <!-- NOTIFICACIONES TEMPORALES -->
    @if(session()->has('message'))
        <div class="bg-green-500 text-white text-center py-2 font-medium">
            {{ session('message') }}
        </div>
    @endif

    @if($checkoutSuccess)
        <div class="bg-orange-500 text-white py-8 px-4 text-center shadow-inner relative">
            <h2 class="text-3xl font-black mb-2">🎉 ¡GRACIAS POR TU SIMULACIÓN DE COMPRA!</h2>
            <p class="text-lg">Tu orden de combos en Watun Wasi ha sido procesada con éxito.</p>
            <button wire:click="$set('checkoutSuccess', false)" class="mt-4 px-6 py-2 bg-white text-orange-600 font-bold rounded-full hover:bg-gray-100 transition shadow-sm">
                Seguir Comprando
            </button>
        </div>
    @endif

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- COLUMNA IZQUIERDA: CATEGORÍAS (Sidebar Estilo Promart) -->
        <aside class="space-y-4 col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-500 text-white px-4 py-3 font-bold text-lg flex items-center justify-between">
                    <span>Categorías</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
                
                <!-- Acordeón colapsable -->
                <div class="divide-y divide-gray-200">
                    <!-- Categorías de combos -->
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2.5 flex items-center justify-between">
                            <span>Tipo de Proyecto</span>
                            <span class="text-orange-600 font-extrabold">+</span>
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="radio" wire:model.live="selectedCategory" value="todos" class="text-orange-600 focus:ring-orange-500">
                                <span class="{{ $selectedCategory == 'todos' ? 'text-orange-600 font-bold' : '' }}">Todos los Combos</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="radio" wire:model.live="selectedCategory" value="baño" class="text-orange-600 focus:ring-orange-500">
                                <span class="{{ $selectedCategory == 'baño' ? 'text-orange-600 font-bold' : '' }}">Combos de Baño ({{ $selectedCategory == 'baño' ? $combos->count() : '25' }})</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="radio" wire:model.live="selectedCategory" value="cocina" class="text-orange-600 focus:ring-orange-500">
                                <span class="{{ $selectedCategory == 'cocina' ? 'text-orange-600 font-bold' : '' }}">Combos de Cocina ({{ $selectedCategory == 'cocina' ? $combos->count() : '4' }})</span>
                            </label>
                        </div>
                    </div>

                    <!-- Filtro por precio -->
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2 flex items-center justify-between">
                            <span>Rango de Precio</span>
                            <span class="text-orange-600 font-extrabold">+</span>
                        </h4>
                        <div class="space-y-1.5 text-sm text-gray-600">
                            <button wire:click="$set('search', '')" class="block hover:text-orange-600 text-left">Mostrar todos</button>
                            <button wire:click="$set('search', 'Zen')" class="block hover:text-orange-600 text-left">Serie Zen</button>
                            <button wire:click="$set('search', 'Mármol')" class="block hover:text-orange-600 text-left">Serie Mármol</button>
                            <button wire:click="$set('search', 'Carrara')" class="block hover:text-orange-600 text-left">Serie Carrara</button>
                            <button wire:click="$set('search', 'Ladrillo')" class="block hover:text-orange-600 text-left">Serie Ladrillo (Cocina)</button>
                        </div>
                    </div>

                    <!-- Información de envío -->
                    <div class="p-4 bg-orange-50">
                        <div class="flex items-start gap-3">
                            <span class="text-orange-600 text-xl">🚚</span>
                            <div>
                                <h5 class="font-bold text-orange-950 text-sm">Watun Wasi Express</h5>
                                <p class="text-xs text-orange-900 mt-0.5">Recoge tus combos gratis hoy mismo en nuestra tienda principal de mejoramiento.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- COLUMNA DERECHA: GRILLA DE COMBOS (Catálogo) -->
        <main class="col-span-1 lg:col-span-3 space-y-6">
            
            <!-- FILTROS HORIZONTALES (Pestañas de Iconos) -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-center gap-3 overflow-x-auto">
                <button 
                    wire:click="$set('selectedCategory', 'todos')"
                    class="flex flex-col items-center gap-1.5 px-4 py-2.5 rounded-lg border {{ $selectedCategory == 'todos' ? 'border-orange-500 bg-orange-50 text-orange-600 font-bold' : 'border-gray-200 hover:bg-gray-50 text-gray-700' }} min-w-[100px] flex-shrink-0 transition"
                >
                    <span class="text-2xl">📦</span>
                    <span class="text-xs uppercase">Todos</span>
                </button>
                
                <button 
                    wire:click="$set('selectedCategory', 'baño')"
                    class="flex flex-col items-center gap-1.5 px-4 py-2.5 rounded-lg border {{ $selectedCategory == 'baño' ? 'border-orange-500 bg-orange-50 text-orange-600 font-bold' : 'border-gray-200 hover:bg-gray-50 text-gray-700' }} min-w-[100px] flex-shrink-0 transition"
                >
                    <span class="text-2xl">🚽</span>
                    <span class="text-xs uppercase">Baños</span>
                </button>

                <button 
                    wire:click="$set('selectedCategory', 'cocina')"
                    class="flex flex-col items-center gap-1.5 px-4 py-2.5 rounded-lg border {{ $selectedCategory == 'cocina' ? 'border-orange-500 bg-orange-50 text-orange-600 font-bold' : 'border-gray-200 hover:bg-gray-50 text-gray-700' }} min-w-[100px] flex-shrink-0 transition"
                >
                    <span class="text-2xl">🍳</span>
                    <span class="text-xs uppercase">Cocinas</span>
                </button>
            </div>

            <!-- CABECERA DE LA GRILLA -->
            <div class="flex items-center justify-between text-gray-600">
                <span>Mostrando <strong>{{ $combos->count() }}</strong> combinaciones</span>
                <span class="text-sm">Ordenar por: <strong>Recomendados</strong></span>
            </div>

            <!-- GRILLA DE TARJETAS -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($combos as $combo)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md border border-gray-200 overflow-hidden flex flex-col justify-between transition duration-200 group">
                        
                        <!-- Imagen & Badge -->
                        <div class="relative bg-gradient-to-br {{ $combo->categoria == 'baño' ? 'from-sky-50 to-indigo-100' : 'from-amber-50 to-orange-100' }} p-6 h-48 flex flex-col justify-between cursor-pointer" wire:click="openDetail({{ $combo->id }})">
                            <span class="absolute top-2 right-2 bg-orange-500 text-white text-[10px] font-black tracking-wider px-2 py-0.5 rounded-full uppercase shadow">
                                COMBO
                            </span>
                            
                            <!-- Ilustración abstracta del combo con Tailwind -->
                            <div class="flex items-center justify-center gap-3 my-auto">
                                @if($combo->categoria == 'baño')
                                    <!-- Baño: Cerámico + Inodoro -->
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-200/50 flex flex-col items-center">
                                        <span class="text-2xl">🧱</span>
                                        <span class="text-[9px] text-gray-400 font-bold mt-1">REVESTIMIENTO</span>
                                    </div>
                                    <span class="text-orange-500 font-black text-xl">+</span>
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-200/50 flex flex-col items-center">
                                        <span class="text-2xl">🚽</span>
                                        <span class="text-[9px] text-gray-400 font-bold mt-1">SANITARIO</span>
                                    </div>
                                @else
                                    <!-- Cocina: Ladrillo/Fachaleta + Repostero -->
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-amber-200/50 flex flex-col items-center">
                                        <span class="text-2xl">🧱</span>
                                        <span class="text-[9px] text-gray-400 font-bold mt-1">PARED</span>
                                    </div>
                                    <span class="text-orange-500 font-black text-xl">+</span>
                                    <div class="bg-white p-3 rounded-lg shadow-sm border border-amber-200/50 flex flex-col items-center">
                                        <span class="text-2xl">🪵</span>
                                        <span class="text-[9px] text-gray-400 font-bold mt-1">REPOSTERO</span>
                                    </div>
                                @endif
                            </div>

                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                Ver Detalles del Ambiente
                            </span>
                        </div>

                        <!-- Información -->
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-orange-600 mb-1">
                                    <span>🏪</span>
                                    <span>WATUN WASI</span>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm group-hover:text-orange-600 transition cursor-pointer" wire:click="openDetail({{ $combo->id }})">
                                    {{ $combo->nombre }}
                                </h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mt-1.5">
                                    {{ $combo->descripcion }}
                                </p>
                            </div>

                            <!-- Precios -->
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl font-black text-gray-900">S/. {{ number_format($combo->precio_oferta, 2) }}</span>
                                    <span class="bg-orange-100 text-orange-700 text-xs font-extrabold px-1.5 py-0.5 rounded">
                                        -{{ $combo->descuento }}%
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    Precio normal: <span class="line-through">S/. {{ number_format($combo->precio_lista, 2) }}</span>
                                </div>
                                
                                <div class="flex items-center gap-1 text-[11px] text-green-600 font-bold mt-2">
                                    <span>⚡</span>
                                    <span>Retira hoy en tienda principal</span>
                                </div>
                            </div>

                            <!-- Botón agregar -->
                            <button 
                                wire:click="addToCart({{ $combo->id }})" 
                                class="w-full py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded shadow-sm hover:shadow transition duration-150 uppercase text-xs"
                            >
                                Agregar al Carrito
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-gray-500">
                        <span class="text-4xl block mb-2">🔍</span>
                        No se encontraron combos para tu búsqueda "{{ $search }}".
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <!-- MODAL DETALLE COMBINACIÓN -->
    @if($showDetailModal && $selectedCombo)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden flex flex-col animate-fade-in">
                <!-- Cabecera modal -->
                <div class="bg-orange-600 text-white p-4 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase font-extrabold tracking-wider bg-orange-800 px-2 py-0.5 rounded">
                            {{ $selectedCombo->categoria }}
                        </span>
                        <h3 class="text-xl font-bold mt-1">{{ $selectedCombo->nombre }}</h3>
                    </div>
                    <button wire:click="closeDetail" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Contenido modal -->
                <div class="p-6 overflow-y-auto space-y-6">
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        {{ $selectedCombo->descripcion }}
                    </p>

                    <!-- Lista de productos incluidos -->
                    <div>
                        <h4 class="font-bold text-gray-800 border-b pb-2 mb-3 flex items-center gap-1.5">
                            <span>📦</span>
                            <span>Lista de Materiales y Sanitarios</span>
                        </h4>
                        
                        <div class="space-y-3">
                            @foreach($selectedCombo->products as $prod)
                                <div class="bg-white border rounded-lg p-3 hover:border-orange-300 transition flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs uppercase font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded">
                                                {{ $prod->pivot->tipo_uso }}
                                            </span>
                                            @if($prod->codigo)
                                                <span class="text-xs font-mono font-bold text-orange-600">Código: {{ $prod->codigo }}</span>
                                            @endif
                                        </div>
                                        <h5 class="font-bold text-gray-800 text-sm">{{ $prod->nombre }}</h5>
                                        <p class="text-xs text-gray-500">
                                            {{ $prod->descripcion }} 
                                            @if($prod->formato) | Formato: {{ $prod->formato }} @endif
                                            @if($prod->color) | Color: {{ $prod->color }} @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-400 block">Precio individual</span>
                                        <span class="font-bold text-gray-800 text-sm">S/. {{ number_format($prod->precio, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Caja de precio y botón -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-orange-800 block">Total en Combo (Descuento incluido)</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-orange-600">S/. {{ number_format($selectedCombo->precio_oferta, 2) }}</span>
                                <span class="text-xs text-gray-400 line-through">S/. {{ number_format($selectedCombo->precio_lista, 2) }}</span>
                            </div>
                        </div>
                        <button 
                            wire:click="addToCart({{ $selectedCombo->id }}); closeDetail();" 
                            class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg shadow uppercase text-xs"
                        >
                            Añadir Combo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- DRAWER DEL CARRITO LATERAL (Slide-out Cart) -->
    <div class="fixed inset-0 overflow-hidden z-50 {{ $showCartDrawer ? 'pointer-events-auto' : 'pointer-events-none' }}">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Overlay translúcido -->
            <div 
                wire:click="$set('showCartDrawer', false)"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 {{ $showCartDrawer ? 'opacity-100' : 'opacity-0' }}"
            ></div>

            <!-- Panel lateral -->
            <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
                <div class="w-screen max-w-md transform transition-all duration-300 {{ $showCartDrawer ? 'translate-x-0' : 'translate-x-full' }} bg-white flex flex-col shadow-2xl">
                    
                    <!-- Cabecera carrito -->
                    <div class="bg-gray-900 text-white p-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span>🛒</span>
                            <span>Mi Carrito Watun Wasi</span>
                        </h3>
                        <button wire:click="$set('showCartDrawer', false)" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido carrito -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        @forelse($cartItems as $item)
                            <div class="border rounded-lg p-3 space-y-3 relative group">
                                <button 
                                    wire:click="removeFromCart({{ $item['combo']->id }})" 
                                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500 focus:outline-none"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                
                                <div class="pr-6">
                                    <span class="text-[10px] uppercase font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">
                                        {{ $item['combo']->categoria }}
                                    </span>
                                    <h4 class="font-bold text-gray-800 text-sm mt-1">{{ $item['combo']->nombre }}</h4>
                                </div>

                                <div class="flex items-center justify-between">
                                    <!-- Controles de cantidad -->
                                    <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                                        <button 
                                            wire:click="updateQuantity({{ $item['combo']->id }}, {{ $item['quantity'] - 1 }})" 
                                            class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold"
                                        >-</button>
                                        <span class="px-3 py-1 font-bold text-gray-800 text-sm">{{ $item['quantity'] }}</span>
                                        <button 
                                            wire:click="updateQuantity({{ $item['combo']->id }}, {{ $item['quantity'] + 1 }})" 
                                            class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold"
                                        >+</button>
                                    </div>
                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <span class="text-[10px] text-gray-400 block">Subtotal</span>
                                        <span class="font-black text-gray-900 text-sm">S/. {{ number_format($item['subtotal'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400">
                                <span class="text-5xl block mb-2">🛒</span>
                                Tu carrito está vacío. ¡Añade combos del catálogo para comenzar!
                            </div>
                        @endforelse
                    </div>

                    <!-- Pie del carrito (Totales y Botón compra) -->
                    @if($cartCount > 0)
                        <div class="bg-gray-50 border-t p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-700">Total a Pagar</span>
                                <span class="text-2xl font-black text-orange-600">S/. {{ number_format($cartTotal, 2) }}</span>
                            </div>
                            <button 
                                wire:click="checkout" 
                                class="w-full py-3 bg-orange-600 hover:bg-orange-700 text-white font-black rounded-lg shadow transition text-center uppercase tracking-wider"
                            >
                                Finalizar Simulación de Compra
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
