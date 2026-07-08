<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            .font-display { font-family: 'Cormorant Garamond', Georgia, serif; }
            .font-body    { font-family: 'Jost', ui-sans-serif, system-ui, sans-serif; }
            .tracking-luxe { letter-spacing: 0.28em; }
            @keyframes hw-auth-zoom { from { transform: scale(1); } to { transform: scale(1.06); } }
            .hw-auth-zoom { animation: hw-auth-zoom 22s ease-out forwards; }
        </style>
    </head>
    <body class="min-h-screen bg-[#faf9f7] antialiased font-body">
        <div class="min-h-svh grid grid-cols-1 lg:grid-cols-2">

            {{-- Panel izquierdo: imagen de marca (solo escritorio) --}}
            <div class="relative hidden lg:block overflow-hidden bg-neutral-900">
                <img src="{{ asset('images/hero-bano.jpg') }}" alt="Hatun Wasi"
                     class="hw-auth-zoom absolute inset-0 w-full h-full object-cover opacity-55">
                <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-900/30 to-neutral-900/50"></div>

                <div class="relative h-full flex flex-col justify-between p-12">
                    <a href="{{ route('store') }}" wire:navigate>
                        <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi"
                             class="h-16 w-auto object-contain"
                             style="filter: brightness(0) invert(1) drop-shadow(0 3px 10px rgba(0,0,0,.4));">
                    </a>

                    <div>
                        <span class="text-[11px] tracking-luxe uppercase text-[#d8c39a]">Panel Administrativo</span>
                        <h2 class="font-display text-5xl font-medium text-white mt-4 leading-tight">
                            Gestiona tu tienda<br>con elegancia
                        </h2>
                        <p class="text-sm text-white/60 mt-4 max-w-sm font-light leading-relaxed">
                            Combos, pedidos y estadísticas de Casas Cerámicos Hatun Wasi en un solo lugar.
                        </p>
                    </div>

                    <div class="text-[10px] tracking-luxe uppercase text-white/40">
                        Jr. Sandia 206 · Juliaca, Perú
                    </div>
                </div>
            </div>

            {{-- Panel derecho: formulario --}}
            <div class="flex flex-col items-center justify-center gap-6 p-6 md:p-10">
                <div class="flex w-full max-w-sm flex-col gap-2">
                    {{-- Logo visible en móvil (cuando no hay panel izquierdo) --}}
                    <a href="{{ route('store') }}" class="lg:hidden flex justify-center mb-4" wire:navigate>
                        <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-16 w-auto object-contain">
                    </a>

                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
