<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-zinc-700 pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-zinc-100">Panel de Control: Productos</h2>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Registra la mercadería de todos los catálogos: combos, revestimientos, accesorios, sanitarios y cerámicos.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.combos') }}" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-bold rounded text-sm transition">
                Ver Combos
            </a>
            <button wire:click="create" class="px-4 py-2 bg-[#A98A4B] hover:bg-[#8a6f3a] hover:shadow-md hover:-translate-y-px text-white font-bold rounded-lg shadow-sm transition-all duration-200 text-sm uppercase">
                + Nuevo Producto
            </button>
        </div>
    </div>

    <!-- BÚSQUEDA Y FILTRO -->
    <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, código o marca..."
                class="w-full pl-10 pr-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200 placeholder-gray-400 dark:placeholder-zinc-500">
            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-zinc-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <select wire:model.live="filterCategoria" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $valor => $etiqueta)
                <option value="{{ $valor }}">{{ $etiqueta }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-zinc-500 md:ml-auto">
            {{ $productos->total() }} productos registrados
        </div>
    </div>

    <!-- TABLA -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-zinc-900 text-xs font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider border-b border-gray-200 dark:border-zinc-700">
                        <th class="p-4">Producto</th>
                        <th class="p-4">Categoría</th>
                        <th class="p-4">Tipo</th>
                        <th class="p-4 text-right">Precio</th>
                        <th class="p-4 text-center">Stock</th>
                        <th class="p-4 text-center">Disponible</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-zinc-300">
                    @forelse($productos as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition duration-150">
                            <td class="p-4">
                                <span class="font-bold text-gray-900 dark:text-white block">{{ $p->nombre }}</span>
                                <span class="text-xs text-gray-400 dark:text-zinc-500 font-mono">
                                    {{ $p->codigo ?: 'S/C' }}@if($p->marca) · {{ $p->marca }}@endif @if($p->formato) · {{ $p->formato }}@endif
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-zinc-300">
                                    {{ $categorias[$p->categoria] ?? $p->categoria ?? '—' }}
                                </span>
                            </td>
                            <td class="p-4 text-xs text-gray-500 dark:text-zinc-400">{{ $p->tipo_producto ?: '—' }}</td>
                            <td class="p-4 text-right font-bold font-mono text-gray-900 dark:text-white">S/. {{ number_format($p->precio, 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p->cantidad <= 10 ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                    {{ $p->cantidad }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <button wire:click="toggleDisponible({{ $p->id }})"
                                    class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full transition {{ $p->disponible ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-200 text-gray-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                                    {{ $p->disponible ? 'Sí' : 'No' }}
                                </button>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <button wire:click="edit({{ $p->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs uppercase">Editar</button>
                                <button wire:click="delete({{ $p->id }})" wire:confirm="¿Eliminar este producto?" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold text-xs uppercase">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 dark:text-zinc-500">No se encontraron productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-zinc-700 [&_a]:dark:text-zinc-300 [&_span]:dark:text-zinc-500">{{ $productos->links() }}</div>
    </div>

    <!-- MODAL CREAR / EDITAR PRODUCTO -->
    @if($showFormModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[92vh] overflow-hidden flex flex-col">

                <div class="bg-[#A98A4B] text-white p-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ $productId ? 'Editar Producto' : 'Registrar Nuevo Producto' }}</h3>
                    <button wire:click="$set('showFormModal', false)" aria-label="Cerrar formulario" class="text-white hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 overflow-y-auto space-y-4 flex-1">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Nombre del Producto *</label>
                            <input type="text" wire:model="nombre" placeholder="Ej: Porcelanato Mármol Carrara"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200 {{ $errors->has('nombre') ? 'border-red-500' : '' }}">
                            @error('nombre') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Categoría *</label>
                            <select wire:model="categoria" class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                                @foreach($categorias as $valor => $etiqueta)
                                    <option value="{{ $valor }}">{{ $etiqueta }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Código</label>
                            <input type="text" wire:model="codigo" placeholder="HW-XXX-001"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm font-mono text-gray-700 dark:text-zinc-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Marca</label>
                            <input type="text" wire:model="marca" placeholder="Porcelatino"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Formato</label>
                            <input type="text" wire:model="formato" placeholder="60 x 60 cm"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Color</label>
                            <input type="text" wire:model="color" placeholder="Gris / Beige"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Tipo de Producto</label>
                            <input type="text" wire:model="tipo_producto" placeholder="Porcelanato / Accesorio"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Precio (S/.) *</label>
                            <input type="number" step="0.01" wire:model="precio"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm font-mono text-gray-700 dark:text-zinc-200 {{ $errors->has('precio') ? 'border-red-500' : '' }}">
                            @error('precio') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Stock *</label>
                            <input type="number" wire:model="cantidad"
                                class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm font-mono text-gray-700 dark:text-zinc-200 {{ $errors->has('cantidad') ? 'border-red-500' : '' }}">
                            @error('cantidad') <span class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-end pb-1.5">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="disponible" class="rounded text-[#A98A4B] focus:ring-[#A98A4B] dark:bg-zinc-700 dark:border-zinc-600">
                                <span class="text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase">Disponible</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase mb-1">Descripción</label>
                        <textarea wire:model="descripcion" rows="2" placeholder="Características, uso recomendado, acabado..."
                            class="w-full border rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#A98A4B] border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 text-sm text-gray-700 dark:text-zinc-200"></textarea>
                    </div>

                    <p class="text-[10px] text-gray-400 dark:text-zinc-500">
                        Los productos de categoría Baño/Cocina quedan disponibles para armar combos. Los de otras categorías aparecen automáticamente en su catálogo público.
                    </p>

                    <!-- Botones -->
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-4 flex items-center justify-end gap-3">
                        <button type="button" wire:click="$set('showFormModal', false)" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded font-bold text-gray-700 dark:text-zinc-300 text-xs uppercase hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#A98A4B] hover:bg-[#8a6f3a] text-white font-bold rounded-lg shadow-sm text-xs uppercase focus:outline-none">
                            <span wire:loading.remove wire:target="save">Guardar Producto</span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
