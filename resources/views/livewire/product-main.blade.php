<div>
    <h1 class="text-3xl mb-10 border-b-3 pb-1 border-violet-500">Gestión de productos</h1>
    <div class="flex gap-2 mb-4">
        <flux:input wire:model.live="search" placeholder="Buscar producto" icon="magnifying-glass"/>
        <flux:button wire:click="create()" variant="primary" color="violet" icon="plus" class="cursor-pointer">Nuevo</flux:button>
    </div>
    
    <flux:table>
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column>NOMBRE</flux:table.column>
            <flux:table.column>CANTIDAD</flux:table.column>
            <flux:table.column>PRECIO</flux:table.column>
            <flux:table.column>DISPONIBLE</flux:table.column>
            <flux:table.column>OPCIONES</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($productos as $item)
                <flux:table.row>
                    <flux:table.cell class="flex items-center gap-3">
                        {{$item->id}}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">{{ $item->nombre }}</flux:table.cell>
                    <flux:table.cell>{{ $item->cantidad }}</flux:table.cell>
                    <flux:table.cell variant="strong" class="text-right">S/. {{ number_format($item->precio, 2) }}</flux:table.cell>
                    <flux:table.cell class="text-center">
                        <flux:badge size="sm" inset="top bottom" color="{{$item->disponible?'green':'red'}}">
                            {{ $item->disponible?"SI":"NO" }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{$item}})" variant="primary" color="amber" icon="pencil" class="cursor-pointer"></flux:button>
                        <flux:button wire:click="delete({{$item->id}})" wire:confirm="¿Estás seguro de eliminar este producto?" variant="primary" color="red" icon="trash" class="cursor-pointer"></flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    
    <div class="mt-4">
        {{$productos->links()}}
    </div>

    <flux:modal name="showform" flyout>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $id ? 'Editar Producto' : 'Registrar Producto' }}</flux:heading>
            </div>
            <flux:input wire:model="nombre" label="Nombre producto" placeholder="Piedra marfil" />
            <flux:textarea wire:model="descripcion" label="Descipción"/>
            <flux:input wire:model="cantidad" label="Cantidad" placeholder="12"/>
            <flux:input wire:model="precio" label="Precio" placeholder="45.50"/>
            <flux:checkbox wire:model="disponible" label="Disponible"/>
            <div class="flex">
                <flux:spacer />
                <flux:button wire:click="save()" variant="primary" color="violet" icon="arrow-turn-down-right">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
