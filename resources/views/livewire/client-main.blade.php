<div>
    <h1 class="text-3xl mb-10 border-b-3 pb-1 border-violet-500">Gestión de clientes</h1>
    <div class="flex gap-2 mb-4">
        <flux:input wire:model.live="search" placeholder="Buscar cliente por nombre o email..." icon="magnifying-glass"/>
        <flux:button wire:click="create()" variant="primary" color="violet" icon="plus" class="cursor-pointer">Nuevo</flux:button>
    </div>
    
    <flux:table>
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>NOMBRE</flux:table.column>
            <flux:table.column>EMAIL</flux:table.column>
            <flux:table.column>TELÉFONO</flux:table.column>
            <flux:table.column>PRODUCTOS CONECTADOS</flux:table.column>
            <flux:table.column>OPCIONES</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($clientes as $item)
                <flux:table.row>
                    <flux:table.cell class="flex items-center gap-3">
                        {{ $item->id }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap font-medium">{{ $item->nombre }}</flux:table.cell>
                    <flux:table.cell>{{ $item->email }}</flux:table.cell>
                    <flux:table.cell>{{ $item->telefono ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-wrap gap-1 max-w-xs">
                            @forelse ($item->products as $prod)
                                <flux:badge size="sm" inset="top bottom" color="purple">
                                    {{ $prod->nombre }}
                                </flux:badge>
                            @empty
                                <span class="text-xs text-zinc-400">Sin productos</span>
                            @endforelse
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{ $item }})" variant="primary" color="amber" icon="pencil" class="cursor-pointer"></flux:button>
                        <flux:button wire:click="delete({{ $item->id }})" wire:confirm="¿Estás seguro de eliminar este cliente?" variant="primary" color="red" icon="trash" class="cursor-pointer"></flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{ $clientes->links() }}
    </div>

    <flux:modal name="showform" flyout>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $id ? 'Editar Cliente' : 'Registrar Cliente' }}</flux:heading>
            </div>
            
            <flux:input wire:model="nombre" label="Nombre cliente" placeholder="Ej. Juan Pérez" />
            <flux:input wire:model="email" label="Email" placeholder="Ej. juan@correo.com" />
            <flux:input wire:model="telefono" label="Teléfono" placeholder="Ej. +51 987654321" />
            <flux:textarea wire:model="direccion" label="Dirección" placeholder="Ej. Av. Larco 123, Miraflores" />
            
            <div class="space-y-2">
                <flux:label>Conectar Productos</flux:label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-zinc-200 dark:border-zinc-700 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800">
                    @forelse ($allProducts as $prod)
                        <flux:checkbox wire:model="selectedProducts" value="{{ $prod->id }}" label="{{ $prod->nombre }} (S/. {{ number_format($prod->precio, 2) }})" />
                    @empty
                        <span class="text-xs text-zinc-500 col-span-2">No hay productos registrados para conectar.</span>
                    @endforelse
                </div>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="save()" variant="primary" color="violet" icon="arrow-turn-down-right">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
