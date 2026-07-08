<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-zinc-700 pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-zinc-100">Panel de Control: Combinaciones</h2>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Gestiona los ambientes del catálogo Hatun Wasi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-bold rounded text-sm transition">
                Ver Pedidos
            </a>
            <button wire:click="create" class="px-4 py-2 bg-[#A98A4B] hover:bg-[#8a6f3a] hover:shadow-md hover:-translate-y-px text-white font-bold rounded-lg shadow-sm transition-all duration-200 text-sm uppercase">
                + Crear Combo
            </button>
        </div>
    </div>

    <!-- BÚSQUEDA -->
    <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-1/3 relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre o categoría..."
                class="w-full pl-10 pr-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200 placeholder-gray-400 dark:placeholder-zinc-500"
            >
            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-zinc-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-zinc-500">
            <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
            Base de datos conectada
        </div>
    </div>

    <!-- TABLA -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-zinc-900 text-xs font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider border-b border-gray-200 dark:border-zinc-700">
                        <th class="p-4">Imagen</th>
                        <th class="p-4">Combo / Ambiente</th>
                        <th class="p-4">Categoría</th>
                        <th class="p-4 text-right">Precio Lista</th>
                        <th class="p-4 text-right">Precio Oferta</th>
                        <th class="p-4 text-center">Descuento</th>
                        <th class="p-4 text-center">Productos</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-zinc-300">
                    @forelse($combos as $combo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition duration-150">
                            <td class="p-4 w-20">
                                @if($combo->imagen)
                                    <div class="h-16 w-12 rounded border border-gray-200 dark:border-zinc-600 bg-[#fbfbfb] dark:bg-zinc-900 flex items-center justify-center overflow-hidden shadow-xs">
                                        <img src="{{ asset('images/combos/' . $combo->imagen) }}" alt="{{ $combo->nombre }}" loading="lazy" decoding="async" class="w-full h-full object-contain p-0.5">
                                    </div>
                                @else
                                    <div class="h-16 w-12 bg-[#A98A4B]/10 dark:bg-[#A98A4B]/10 rounded border border-[#A98A4B]/15 dark:border-[#A98A4B]/20 flex items-center justify-center text-xl shadow-inner">
                                        @if($combo->categoria == 'baño') <svg class="w-5 h-5 text-[#A98A4B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a7 7 0 007-7c0-4-7-11-7-11S5 10 5 14a7 7 0 007 7z"/></svg> @else <svg class="w-5 h-5 text-[#A98A4B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3s5 4.5 5 9a5 5 0 01-10 0c0-1.5.5-3 1.5-4.5 0 0 .5 2 2 2.5C10 8 10.5 5 12 3z"/></svg> @endif
                                    </div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-900 dark:text-white block">{{ $combo->nombre }}</span>
                                <span class="text-xs text-gray-400 dark:text-zinc-500 line-clamp-1 mt-0.5">{{ $combo->descripcion }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-xs uppercase font-extrabold px-2 py-0.5 rounded {{ $combo->categoria == 'baño' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20' : 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-500/20' }}">
                                    {{ $combo->categoria }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-mono text-gray-400 dark:text-zinc-500">S/. {{ number_format($combo->precio_lista, 2) }}</td>
                            <td class="p-4 text-right font-bold font-mono text-gray-900 dark:text-white">S/. {{ number_format($combo->precio_oferta, 2) }}</td>
                            <td class="p-4 text-center font-extrabold text-[#A98A4B] dark:text-[#d8c39a]">{{ $combo->descuento }}%</td>
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-bold px-2.5 py-0.5 rounded-full text-xs">{{ $combo->products->count() }} items</span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <button wire:click="edit({{ $combo->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs uppercase">Editar</button>
                                <button wire:click="delete({{ $combo->id }})" wire:confirm="¿Eliminar este combo?" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold text-xs uppercase">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-400 dark:text-zinc-500">No se encontraron combos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-zinc-700 [&_a]:dark:text-zinc-300 [&_span]:dark:text-zinc-500">{{ $combos->links() }}</div>
    </div>

    <!-- =====================================================================
         MODAL FORMULARIO CREAR / EDITAR
    ===================================================================== -->
    @if($showFormModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-2xl max-w-3xl w-full max-h-[92vh] overflow-hidden flex flex-col">

                <div class="bg-[#A98A4B] text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ $comboId ? 'Editar Combo' : 'Crear Nuevo Combo' }}</h3>
                    <button wire:click="$set('showFormModal', false)" aria-label="Cerrar formulario" class="text-white hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 overflow-y-auto space-y-5 flex-1">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        <!-- Columna principal (3/4) -->
                        <div class="md:col-span-3 space-y-4">

                            <!-- Nombre y Categoría -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Nombre del Combo *</label>
                                    <input type="text" wire:model="nombre" placeholder="Ej: COMBO Hatun Wasi: Serie Zen"
                                        class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200 {{ $errors->has('nombre') ? 'border-red-500 dark:border-red-500' : '' }}">
                                    @error('nombre') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Categoría *</label>
                                    <select wire:model="categoria" class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                                        <option value="baño">Baño</option>
                                        <option value="cocina">Cocina</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Descripción</label>
                                <textarea wire:model="descripcion" rows="2" placeholder="Breve descripción del ambiente..."
                                    class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200"></textarea>
                            </div>

                            <!-- Precios -->
                            <div class="grid grid-cols-3 gap-4 bg-gray-50 dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Precio Lista (S/.)</label>
                                    <input type="number" step="0.01" wire:model.live="precio_lista"
                                        class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-mono text-gray-700 dark:text-zinc-200 {{ $errors->has('precio_lista') ? 'border-red-500 dark:border-red-500' : '' }}">
                                    @error('precio_lista') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Precio Oferta (S/.)</label>
                                    <input type="number" step="0.01" wire:model.live="precio_oferta"
                                        class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-mono text-gray-700 dark:text-zinc-200 {{ $errors->has('precio_oferta') ? 'border-red-500 dark:border-red-500' : '' }}">
                                    @error('precio_oferta') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Descuento (%)</label>
                                    <div class="bg-gray-100 dark:bg-zinc-700 rounded px-3 py-1.5 font-bold font-mono text-sm text-[#A98A4B] dark:text-[#d8c39a] border border-gray-300 dark:border-zinc-600">
                                        {{ $descuento }}%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna imagen (1/4) -->
                        <div class="md:col-span-1 space-y-3">
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Imagen del Combo</label>

                            <!-- Vista previa -->
                            <div class="w-full aspect-[3/4] rounded-lg border-2 border-dashed border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-900 flex items-center justify-center overflow-hidden">
                                @if($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-contain p-1" alt="Vista previa">
                                @elseif($imagenActual)
                                    <img src="{{ asset('images/combos/' . $imagenActual) }}" class="w-full h-full object-contain p-1" alt="Imagen actual">
                                @else
                                    <div class="text-center text-gray-400 dark:text-zinc-500">
                                        <span class="block text-gray-400 dark:text-zinc-500"><svg class="w-8 h-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Sin imagen</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Input de archivo -->
                            <div>
                                <label class="block w-full text-center py-2 px-3 border border-gray-300 dark:border-zinc-600 rounded cursor-pointer bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-700 text-xs font-bold text-gray-600 dark:text-zinc-300 transition">
                                    <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg> Subir imagen
                                    <input type="file" wire:model="foto" accept="image/*" class="hidden">
                                </label>
                                @error('foto') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                                <div wire:loading wire:target="foto" class="text-xs text-[#A98A4B] dark:text-[#d8c39a] mt-1 text-center">Cargando...</div>
                                @if($imagenActual && !$foto)
                                    <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1 text-center truncate" title="{{ $imagenActual }}">{{ $imagenActual }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Asignar productos -->
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-200 dark:border-zinc-700 pb-1.5 mb-2.5">
                            <h4 class="font-bold text-gray-800 dark:text-zinc-200 text-xs uppercase">Asignar Productos al Combo</h4>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="font-bold {{ count($selectedProducts) ? 'text-[#A98A4B] dark:text-[#d8c39a]' : 'text-gray-400 dark:text-zinc-500' }}">
                                    {{ count($selectedProducts) }} seleccionado{{ count($selectedProducts) === 1 ? '' : 's' }}
                                </span>
                                @if(count($selectedProducts))
                                    <span class="text-gray-400 dark:text-zinc-500">·</span>
                                    <span class="font-mono text-gray-600 dark:text-zinc-300">Suma: S/. {{ number_format($sumaSeleccionados, 2) }}</span>
                                    <button type="button" wire:click="usarSumaComoPrecioLista"
                                        class="text-[10px] font-bold uppercase text-[#A98A4B] dark:text-[#d8c39a] hover:text-[#6d5426] dark:hover:text-[#e8d5a3] border border-[#A98A4B]/25 dark:border-[#A98A4B]/30 rounded px-2 py-0.5 transition">
                                        Usar como precio lista
                                    </button>
                                @endif
                            </div>
                        </div>

                        @error('selectedProducts')
                            <p class="text-xs text-red-500 dark:text-red-400 mb-2">{{ $message }}</p>
                        @enderror

                        <!-- Buscador de productos -->
                        <div class="relative mb-2">
                            <input type="text" wire:model.live.debounce.300ms="productSearch"
                                placeholder="Buscar producto por nombre, código o marca..."
                                class="w-full pl-8 pr-4 py-1.5 border rounded text-xs focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-gray-700 dark:text-zinc-200 placeholder-gray-400 dark:placeholder-zinc-500">
                            <svg class="h-4 w-4 absolute left-2.5 top-2 text-gray-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <div class="border border-gray-200 dark:border-zinc-700 rounded-lg max-h-56 overflow-y-auto divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($allProducts as $product)
                                <div class="p-2.5 flex items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                    <label class="flex items-center gap-2 cursor-pointer flex-1">
                                        <input type="checkbox" wire:model.live="selectedProducts" value="{{ $product->id }}" class="rounded text-[#A98A4B] focus:ring-[#A98A4B] dark:bg-zinc-700 dark:border-zinc-600">
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-900 dark:text-white block">{{ $product->nombre }}</span>
                                            <span class="text-gray-400 dark:text-zinc-500 font-mono">
                                                Cód: {{ $product->codigo ?: 'S/C' }} | S/. {{ $product->precio }}@if($product->formato) | {{ $product->formato }}@endif
                                            </span>
                                        </div>
                                    </label>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        {{-- Stock disponible --}}
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $product->cantidad <= 10 ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                            Stock: {{ $product->cantidad }}
                                        </span>
                                        @if(in_array((string)$product->id, $selectedProducts))
                                            <select wire:model="productRoles.{{ $product->id }}" class="w-36 border rounded px-2 py-1 text-xs border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-1 focus:ring-[#A98A4B] text-gray-600 dark:text-zinc-300">
                                                <option value="pared">Pared</option>
                                                <option value="piso">Piso</option>
                                                <option value="detalle_decorativo">Detalle/Lápiz</option>
                                                <option value="sanitario">Sanitario</option>
                                                <option value="mobiliario">Mobiliario</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-gray-400 dark:text-zinc-500">
                                    No se encontraron productos para "{{ $productSearch }}".
                                </div>
                            @endforelse
                        </div>
                        <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1.5">
                            Los productos de la categoría "{{ ucfirst($categoria) }}" aparecen primero. Marca cada producto y asígnale su rol en el ambiente.
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-4 flex items-center justify-end gap-3">
                        <button type="button" wire:click="$set('showFormModal', false)" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded font-bold text-gray-700 dark:text-zinc-300 text-xs uppercase hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#A98A4B] hover:bg-[#8a6f3a] hover:shadow-md hover:-translate-y-px text-white font-bold rounded-lg shadow-sm transition-all duration-200 text-xs uppercase focus:outline-none">
                            <span wire:loading.remove wire:target="save">Guardar Cambios</span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
