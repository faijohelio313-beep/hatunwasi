<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">
    
    <!-- ENCABEZADO Y ACCIONES -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Panel de Control: Combinaciones</h2>
            <p class="text-xs text-gray-500 mt-1">Gestiona los ambientes del catálogo de mejoramiento del hogar de Watun Wasi.</p>
        </div>
        <button 
            wire:click="create"
            class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded shadow-sm text-sm uppercase transition duration-150"
        >
            + Crear Combo
        </button>
    </div>

    <!-- BUSQUEDA -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-1/3 relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por nombre o categoría..." 
                class="w-full pl-10 pr-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700"
            >
            <div class="absolute left-3 top-2.5 text-gray-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <div class="text-xs text-gray-400">
            Base de datos SQLite conectada
        </div>
    </div>

    <!-- TABLA DE LISTADO -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider border-b">
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
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($combos as $combo)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <!-- Imagen representativa -->
                            <td class="p-4 w-20">
                                <div class="h-10 w-12 bg-orange-100 rounded flex items-center justify-center text-lg shadow-inner">
                                    {{ $combo->categoria == 'baño' ? '🚽' : '🍳' }}
                                </div>
                            </td>
                            <!-- Nombre e info -->
                            <td class="p-4">
                                <span class="font-bold text-gray-900 block">{{ $combo->nombre }}</span>
                                <span class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ $combo->descripcion }}</span>
                            </td>
                            <!-- Categoria -->
                            <td class="p-4">
                                <span class="text-xs uppercase font-extrabold px-2 py-0.5 rounded {{ $combo->categoria == 'baño' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    {{ $combo->categoria }}
                                </span>
                            </td>
                            <!-- Precios -->
                            <td class="p-4 text-right font-mono text-gray-400">
                                S/. {{ number_format($combo->precio_lista, 2) }}
                            </td>
                            <td class="p-4 text-right font-bold font-mono text-gray-900">
                                S/. {{ number_format($combo->precio_oferta, 2) }}
                            </td>
                            <!-- Descuento -->
                            <td class="p-4 text-center font-extrabold text-orange-600">
                                {{ $combo->descuento }}%
                            </td>
                            <!-- Contador de productos -->
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 text-gray-700 font-bold px-2.5 py-0.5 rounded-full text-xs">
                                    {{ $combo->products->count() }} items
                                </span>
                            </td>
                            <!-- Acciones -->
                            <td class="p-4 text-center space-x-2">
                                <button 
                                    wire:click="edit({{ $combo->id }})" 
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-xs uppercase"
                                >
                                    Editar
                                </button>
                                <button 
                                    wire:click="delete({{ $combo->id }})" 
                                    wire:confirm="¿Estás seguro de que deseas eliminar este combo?"
                                    class="text-red-600 hover:text-red-800 font-semibold text-xs uppercase"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-400">
                                No se encontraron combos disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t">
            {{ $combos->links() }}
        </div>
    </div>

    <!-- MODAL DE FORMULARIO (Crear/Editar) -->
    @if($showFormModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col animate-fade-in">
                
                <!-- Cabecera -->
                <div class="bg-orange-600 text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">
                        {{ $comboId ? 'Editar Combo de Ambiente' : 'Crear Nuevo Combo de Ambiente' }}
                    </h3>
                    <button wire:click="$set('showFormModal', false)" class="text-white hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="save" class="p-6 overflow-y-auto space-y-4 flex-1">
                    
                    <!-- Fila 1: Nombre y Categoria -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nombre del Combo</label>
                            <input 
                                type="text" 
                                wire:model="nombre" 
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700"
                                placeholder="Ej: COMBO Watun Wasi: Serie Zen Geométrica"
                            >
                            @error('nombre') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Categoría</label>
                            <select 
                                wire:model="categoria" 
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700"
                            >
                                <option value="baño">Baño</option>
                                <option value="cocina">Cocina</option>
                            </select>
                            @error('categoria') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 2: Descripcion -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Descripción</label>
                        <textarea 
                            wire:model="descripcion" 
                            rows="2"
                            class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700"
                            placeholder="Breve descripción del ambiente y su temática..."
                        ></textarea>
                        @error('descripcion') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fila 3: Precios y Descuento -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-3 rounded-lg border">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Precio Lista (S/.)</label>
                            <input 
                                type="number" 
                                step="0.01"
                                wire:model.live="precio_lista" 
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700 font-mono"
                            >
                            @error('precio_lista') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Precio Oferta (S/.)</label>
                            <input 
                                type="number" 
                                step="0.01"
                                wire:model.live="precio_oferta" 
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-500 border-gray-300 text-sm text-gray-700 font-mono"
                            >
                            @error('precio_oferta') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Descuento (%)</label>
                            <div class="bg-gray-100 rounded px-3 py-1.5 font-bold font-mono text-sm text-orange-600 border border-gray-300">
                                {{ $descuento }}%
                            </div>
                        </div>
                    </div>

                    <!-- Asignación de Productos del catálogo -->
                    <div>
                        <h4 class="font-bold text-gray-800 text-xs uppercase border-b pb-1.5 mb-2.5">
                            Asignar Productos al Combo
                        </h4>
                        
                        <!-- Lista con scroll -->
                        <div class="border rounded-lg max-h-56 overflow-y-auto divide-y">
                            @foreach($allProducts as $product)
                                <div class="p-2.5 flex items-center justify-between gap-4 hover:bg-gray-50">
                                    <label class="flex items-center gap-2 cursor-pointer flex-1">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectedProducts" 
                                            value="{{ $product->id }}" 
                                            class="rounded text-orange-600 focus:ring-orange-500"
                                        >
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-900 block">{{ $product->nombre }}</span>
                                            <span class="text-gray-400 font-mono">Código: {{ $product->codigo ?: 'S/C' }} | S/. {{ $product->precio }}</span>
                                        </div>
                                    </label>
                                    
                                    <!-- Selección de rol/tipo de uso si el producto está seleccionado -->
                                    @if(in_array((string)$product->id, $selectedProducts))
                                        <div class="w-40">
                                            <select 
                                                wire:model="productRoles.{{ $product->id }}"
                                                class="w-full border rounded px-2 py-1 text-xs border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500 text-gray-600"
                                            >
                                                <option value="pared">Pared</option>
                                                <option value="piso">Piso</option>
                                                <option value="detalle_decorativo">Detalle/Lápiz/Inserto</option>
                                                <option value="sanitario">Sanitario</option>
                                                <option value="mobiliario">Mobiliario</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="border-t pt-4 flex items-center justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="$set('showFormModal', false)"
                            class="px-4 py-2 border rounded font-bold text-gray-700 text-xs uppercase hover:bg-gray-50 focus:outline-none"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded shadow-sm text-xs uppercase focus:outline-none"
                        >
                            Guardar Cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif

</div>
