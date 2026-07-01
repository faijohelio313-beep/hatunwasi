<div class="min-h-screen bg-white flex flex-col font-body text-neutral-800">

    {{-- ===================================================================
         BARRA SUPERIOR FINA
    =================================================================== --}}
    <div class="hidden md:block bg-neutral-50 border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-2 flex items-center justify-between text-[11px] tracking-wider text-neutral-500 uppercase">
            <span class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                Recojo gratis en tienda · Juliaca
            </span>
            <span>Diseño de interiores · Baños & Cocinas</span>
        </div>
    </div>

    {{-- ===================================================================
         CABECERA PRINCIPAL — logo serif centrado
    =================================================================== --}}
    <header class="bg-white sticky top-0 z-40 border-b border-neutral-100 shadow-[0_2px_24px_-8px_rgba(0,0,0,0.12)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-2 grid grid-cols-3 items-center gap-4">

            {{-- Izquierda: buscador --}}
            <div class="hidden lg:flex flex-col gap-1">
                <div class="relative max-w-xs">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar ambiente o código…"
                        class="w-full border-0 border-b border-neutral-200 bg-transparent pl-6 pr-2 py-1.5 text-xs text-neutral-600 placeholder-neutral-300 focus:outline-none focus:border-[#A98A4B] transition"
                    >
                    <svg class="h-3.5 w-3.5 absolute left-0 top-2 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <span class="text-[10px] tracking-widest text-neutral-300 uppercase pl-0.5">Buscar en catálogo</span>
            </div>

            {{-- Centro: logo --}}
            <div class="col-span-1 flex flex-col items-center justify-center cursor-pointer py-1" wire:click="$set('selectedCategory', 'todos')">
                <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-28 sm:h-32 w-auto object-contain" style="filter: drop-shadow(0 2px 8px rgba(0,0,0,0.08))">
            </div>

            {{-- Derecha: cita + carrito --}}
            <div class="flex flex-col items-end justify-center gap-3">
                <a href="{{ route('admin.combos') }}" class="hidden md:inline-flex items-center gap-2 bg-[#A98A4B] hover:bg-[#8a6f3a] text-white px-5 py-2 text-[10px] tracking-widest uppercase transition-colors duration-300 shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Cita en tienda
                </a>
                <button wire:click="$set('showCartDrawer', true)" class="relative flex items-center gap-2 text-neutral-500 hover:text-[#A98A4B] transition focus:outline-none group">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    <span class="text-[11px] tracking-widest uppercase hidden md:block">Carrito</span>
                    @if($cartCount > 0)
                        <span class="bg-[#A98A4B] text-white text-[10px] font-medium rounded-full h-[18px] min-w-[18px] flex items-center justify-center px-1">{{ $cartCount }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Navegación de categorías --}}
        <nav class="border-t border-neutral-100">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-center gap-10 text-[12px] tracking-widest uppercase">
                @foreach([['todos','Todos los Ambientes'],['baño','Baños'],['cocina','Cocinas']] as [$val,$label])
                    <button
                        wire:click="$set('selectedCategory', '{{ $val }}')"
                        class="relative py-3.5 transition-colors duration-200 {{ $selectedCategory == $val ? 'text-neutral-900' : 'text-neutral-500 hover:text-neutral-900' }}"
                    >
                        {{ $label }}
                        <span class="absolute left-0 bottom-0 h-px bg-[#A98A4B] transition-all duration-300 {{ $selectedCategory == $val ? 'w-full' : 'w-0' }}"></span>
                    </button>
                @endforeach
            </div>
        </nav>
    </header>

    {{-- ===================================================================
         BANNER ÉXITO TRAS PEDIDO
    =================================================================== --}}
    @if($checkoutSuccess)
        <div class="bg-neutral-900 text-white py-8 px-4 text-center">
            <h2 class="font-display text-3xl mb-2">Gracias por tu pedido</h2>
            <p class="text-sm text-neutral-300 tracking-wide">Tu solicitud <strong class="text-[#A98A4B]">#{{ str_pad($checkoutOrderId, 5, '0', STR_PAD_LEFT) }}</strong> fue registrada. Te contactaremos muy pronto.</p>
            <button wire:click="$set('checkoutSuccess', false)" class="mt-4 px-7 py-2.5 border border-white/40 hover:border-[#A98A4B] hover:text-[#A98A4B] text-xs tracking-widest uppercase transition">
                Seguir explorando
            </button>
        </div>
    @endif

    {{-- ===================================================================
         HERO — cambia según categoría seleccionada
    =================================================================== --}}
    @php
        $heroImg    = $selectedCategory === 'baño'   ? 'hero-bano.png'
                    : ($selectedCategory === 'cocina' ? 'hero-cocina.png'
                    : 'hero.png');

        $heroTag    = $selectedCategory === 'baño'   ? 'Espacios de bienestar'
                    : ($selectedCategory === 'cocina' ? 'El corazón del hogar'
                    : 'Diseñamos espacios que');

        $heroTitle  = $selectedCategory === 'baño'   ? "Baños que\nenamoran"
                    : ($selectedCategory === 'cocina' ? "Cocinas que\ninspiran"
                    : "Inspiran tu\nvida diaria");

        $heroText   = $selectedCategory === 'baño'   ? 'Revestimientos y sanitarios de primera para crear el baño de tus sueños con estilo y funcionalidad.'
                    : ($selectedCategory === 'cocina' ? 'Combinaciones de cerámica y porcelánico para transformar tu cocina en un espacio único y moderno.'
                    : 'Combinaciones diseñadas para renovar tu espacio con elegancia, coherencia y materiales de primera.');

        $heroBtnLabel = $selectedCategory === 'baño'   ? 'Ver Combos de Baño'
                      : ($selectedCategory === 'cocina' ? 'Ver Combos de Cocina'
                      : 'Ver Colecciones');
    @endphp

    <section class="relative h-[420px] sm:h-[560px] w-full overflow-hidden bg-neutral-900">
        <img src="{{ asset('images/' . $heroImg) }}" alt="Hatun Wasi" class="absolute inset-0 w-full h-full object-cover object-center opacity-70 transition-opacity duration-700">
        <div class="absolute inset-0 bg-gradient-to-r from-neutral-900/80 via-neutral-900/40 to-neutral-900/10"></div>
        <div class="relative h-full flex flex-col items-start justify-center px-8 sm:px-16 lg:px-24 text-white max-w-7xl mx-auto w-full">
            <span class="text-[11px] tracking-luxe uppercase text-[#A98A4B] mb-4">{{ $heroTag }}</span>
            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-medium mb-5 leading-tight">
                {!! nl2br(e($heroTitle)) !!}
            </h1>
            <p class="max-w-md font-body text-sm sm:text-base font-light text-white/80 leading-relaxed mb-8">
                {{ $heroText }}
            </p>
            <div class="flex flex-wrap gap-4">
                <button wire:click="$set('selectedCategory', '{{ $selectedCategory }}')" class="bg-[#A98A4B] hover:bg-[#8a6f3a] text-white px-7 py-3 text-[11px] tracking-widest uppercase transition-colors duration-300">
                    {{ $heroBtnLabel }}
                </button>
                <button wire:click="$set('showCartDrawer', true)" class="border border-white/60 hover:border-white text-white px-7 py-3 text-[11px] tracking-widest uppercase transition-colors duration-300">
                    Cotizar mi Proyecto
                </button>
            </div>
        </div>
    </section>

    {{-- Migas de pan --}}
    <div class="max-w-7xl mx-auto w-full px-6 lg:px-8 py-5 text-[12px] tracking-wide text-neutral-400 uppercase">
        Inicio <span class="mx-2">/</span> Productos <span class="mx-2">/</span>
        <span class="text-neutral-700">{{ $selectedCategory === 'todos' ? 'Ambientes' : ($selectedCategory === 'baño' ? 'Baños' : 'Cocinas') }}</span>
    </div>

    {{-- ===================================================================
         BARRA DE BENEFICIOS
    =================================================================== --}}
    <section class="bg-white border-b border-neutral-100 py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-[#A98A4B]/40 text-[#A98A4B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base text-neutral-900">Diseño personalizado</h4>
                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Te ayudamos a crear espacios únicos y funcionales.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-[#A98A4B]/40 text-[#A98A4B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base text-neutral-900">Entrega rápida</h4>
                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Entregas seguras y puntuales en Juliaca y todo el Perú.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-[#A98A4B]/40 text-[#A98A4B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base text-neutral-900">Instalación profesional</h4>
                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Contamos con expertos para una instalación perfecta.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-[#A98A4B]/40 text-[#A98A4B]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base text-neutral-900">Materiales premium</h4>
                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Cerámicos, porcelánicos y sanitarios de la más alta calidad.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================
         GRILLA DE COMBOS
    =================================================================== --}}
    <main class="max-w-7xl mx-auto w-full px-6 lg:px-8 pb-24">

        <div class="flex items-end justify-between border-b border-neutral-200 pb-4 mb-10">
            <div>
                <h2 class="font-display text-3xl text-neutral-900">Nuestras Combinaciones</h2>
                <p class="text-xs tracking-wide text-neutral-400 uppercase mt-1">{{ $combos->total() }} ambientes disponibles</p>
            </div>
            <span class="hidden sm:block text-xs tracking-wide text-neutral-400 uppercase">Página {{ $combos->currentPage() }} / {{ $combos->lastPage() }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
            @forelse($combos as $combo)
                <div class="group flex flex-col">
                    {{-- Imagen --}}
                    <div class="relative aspect-[4/5] w-full overflow-hidden bg-neutral-50 cursor-pointer" wire:click="openDetail({{ $combo->id }})">
                        @if($combo->imagen)
                            <img src="{{ asset('images/combos/' . $combo->imagen) }}" alt="{{ $combo->nombre }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-300 text-6xl font-display">{{ $combo->categoria == 'baño' ? '◍' : '◫' }}</div>
                        @endif
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur text-neutral-700 text-[10px] tracking-widest uppercase px-3 py-1">{{ $combo->descuento }}% Dto.</span>
                        <div class="absolute inset-0 bg-neutral-900/0 group-hover:bg-neutral-900/10 transition-colors duration-500 flex items-end justify-center pb-6">
                            <span class="opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-500 bg-white text-neutral-800 text-[11px] tracking-widest uppercase px-6 py-2.5">Ver Ambiente</span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="bg-neutral-50 px-5 py-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 text-[10px] tracking-widest uppercase text-[#A98A4B] mb-1.5">
                            {{ $combo->categoria }} · {{ $combo->products->count() }} piezas
                        </div>
                        <h3 class="font-display text-xl text-neutral-900 leading-snug cursor-pointer group-hover:text-[#A98A4B] transition-colors" wire:click="openDetail({{ $combo->id }})">
                            {{ $combo->nombre }}
                        </h3>
                        <p class="text-[13px] text-neutral-500 line-clamp-2 mt-2 leading-relaxed flex-1">{{ $combo->descripcion }}</p>

                        <div class="flex items-end justify-between mt-5 pt-4 border-t border-neutral-200">
                            <div>
                                <div class="text-[11px] text-neutral-400 line-through">S/ {{ number_format($combo->precio_lista, 2) }}</div>
                                <div class="font-display text-2xl text-neutral-900">S/ {{ number_format($combo->precio_oferta, 2) }}</div>
                            </div>
                            <button wire:click="addToCart({{ $combo->id }})" class="border border-neutral-800 text-neutral-800 hover:bg-neutral-900 hover:text-white px-5 py-2.5 text-[11px] tracking-widest uppercase transition-colors duration-300">
                                Añadir
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="font-display text-5xl text-neutral-200 mb-3">∅</div>
                    <p class="text-neutral-500 tracking-wide">No encontramos ambientes para “{{ $search }}”.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($combos->hasPages())
            <div class="flex justify-center mt-16 [&_a]:!text-neutral-600 [&_span]:!text-neutral-900">
                {{ $combos->links() }}
            </div>
        @endif
    </main>

    {{-- ===================================================================
         BANNER CTA
    =================================================================== --}}
    <section class="bg-[#1a2744] py-10 px-6">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5 text-white">
                <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center border border-white/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-xl text-white">¿Listo para renovar tu espacio?</h3>
                    <p class="text-sm text-white/60 mt-0.5">Agenda una cita en tienda y recibe asesoría personalizada.</p>
                </div>
            </div>
            <a href="{{ route('admin.combos') }}" class="flex-shrink-0 bg-[#A98A4B] hover:bg-[#8a6f3a] text-white px-8 py-3 text-[11px] tracking-widest uppercase transition-colors duration-300">
                Agendar Cita
            </a>
        </div>
    </section>

    {{-- ===================================================================
         PIE DE PÁGINA
    =================================================================== --}}
    <footer class="bg-neutral-900 text-neutral-300 mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-16 w-auto object-contain mb-4" style="filter: brightness(0) invert(1);">
                <p class="text-sm text-neutral-400 leading-relaxed max-w-sm">Renovamos tu hogar con combinaciones de baño y cocina diseñadas por expertos. Cerámica, porcelánico y sanitarios de alta gama.</p>
                <div class="flex gap-3 mt-5">
                    <a href="#" class="w-8 h-8 flex items-center justify-center border border-white/20 hover:border-[#A98A4B] hover:text-[#A98A4B] text-white/50 transition-colors duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border border-white/20 hover:border-[#A98A4B] hover:text-[#A98A4B] text-white/50 transition-colors duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center border border-white/20 hover:border-[#A98A4B] hover:text-[#A98A4B] text-white/50 transition-colors duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-[11px] tracking-widest uppercase text-[#A98A4B] mb-4">Productos</h4>
                <ul class="space-y-2 text-sm text-neutral-400">
                    <li><button wire:click="$set('selectedCategory','baño')" class="hover:text-white transition">Combos de Baño</button></li>
                    <li><button wire:click="$set('selectedCategory','cocina')" class="hover:text-white transition">Combos de Cocina</button></li>
                    <li><button wire:click="$set('selectedCategory','todos')" class="hover:text-white transition">Ver Todo</button></li>
                </ul>
            </div>
            <div>
                <h4 class="text-[11px] tracking-widest uppercase text-[#A98A4B] mb-4">Contacto</h4>
                <ul class="space-y-2 text-sm text-neutral-400">
                    <li>Juliaca, Perú</li>
                    <li>+51 987 654 321</li>
                    <li>hola@watunwasi.pe</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-5 text-center text-[11px] tracking-widest uppercase text-neutral-500">
            © {{ date('Y') }} Hatun Wasi · Proyecto Universitario
        </div>
    </footer>

    {{-- ===================================================================
         MODAL DETALLE
    =================================================================== --}}
    @if($showDetailModal && $selectedCombo)
        <div class="fixed inset-0 bg-neutral-900/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white max-w-5xl w-full max-h-[92vh] overflow-hidden flex flex-col shadow-2xl">
                <div class="flex items-center justify-between px-6 py-5 border-b border-neutral-100">
                    <div>
                        <span class="text-[10px] tracking-widest uppercase text-[#A98A4B]">{{ $selectedCombo->categoria }}</span>
                        <h3 class="font-display text-2xl text-neutral-900 mt-0.5">{{ $selectedCombo->nombre }}</h3>
                    </div>
                    <button wire:click="closeDetail" class="text-neutral-400 hover:text-neutral-900 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        <div class="md:col-span-5">
                            @if($selectedCombo->imagen)
                                <div class="w-full aspect-[4/5] overflow-hidden bg-neutral-50">
                                    <img src="{{ asset('images/combos/' . $selectedCombo->imagen) }}" alt="{{ $selectedCombo->nombre }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-full aspect-[4/5] bg-neutral-50 flex items-center justify-center text-7xl font-display text-neutral-300">{{ $selectedCombo->categoria == 'baño' ? '◍' : '◫' }}</div>
                            @endif
                        </div>

                        <div class="md:col-span-7 flex flex-col justify-between space-y-6">
                            <div class="space-y-5">
                                <p class="text-sm text-neutral-600 leading-relaxed">{{ $selectedCombo->descripcion }}</p>

                                <div>
                                    <h4 class="text-[11px] tracking-widest uppercase text-neutral-400 border-b border-neutral-100 pb-2 mb-3 flex items-center justify-between">
                                        <span>Materiales Incluidos</span>
                                        <span class="text-[#A98A4B]">{{ $selectedCombo->products->count() }} piezas</span>
                                    </h4>
                                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                                        @foreach($selectedCombo->products as $prod)
                                            <div class="border-b border-neutral-50 pb-3 flex items-start justify-between gap-4">
                                                <div class="space-y-1">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="text-[9px] uppercase tracking-widest text-[#A98A4B]">{{ str_replace('_', ' ', $prod->pivot->tipo_uso) }}</span>
                                                        @if($prod->codigo)<span class="text-[10px] text-neutral-400">· {{ $prod->codigo }}</span>@endif
                                                    </div>
                                                    <h5 class="font-display text-base text-neutral-800">{{ $prod->nombre }}</h5>
                                                    <p class="text-xs text-neutral-500">
                                                        @if($prod->marca){{ $prod->marca }}@endif
                                                        @if($prod->formato) · {{ $prod->formato }}@endif
                                                        @if($prod->color) · {{ $prod->color }}@endif
                                                    </p>
                                                </div>
                                                <span class="font-display text-base text-neutral-700 flex-shrink-0">S/ {{ number_format($prod->precio, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="bg-neutral-50 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <span class="text-[10px] tracking-widest uppercase text-neutral-400 block">Total en Oferta</span>
                                    <div class="flex items-baseline gap-3">
                                        <span class="font-display text-3xl text-neutral-900">S/ {{ number_format($selectedCombo->precio_oferta, 2) }}</span>
                                        <span class="text-sm text-neutral-400 line-through">S/ {{ number_format($selectedCombo->precio_lista, 2) }}</span>
                                    </div>
                                </div>
                                <button wire:click="addToCart({{ $selectedCombo->id }})" class="w-full sm:w-auto px-8 py-3 bg-neutral-900 hover:bg-[#A98A4B] text-white text-[11px] tracking-widest uppercase transition-colors duration-300">
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================================================================
         DRAWER CARRITO
    =================================================================== --}}
    <div class="fixed inset-0 overflow-hidden z-50 {{ $showCartDrawer ? 'pointer-events-auto' : 'pointer-events-none' }}">
        <div class="absolute inset-0 overflow-hidden">
            <div wire:click="$set('showCartDrawer', false)" class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm transition-opacity duration-300 {{ $showCartDrawer ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
                <div class="w-screen max-w-md transform transition-all duration-300 {{ $showCartDrawer ? 'translate-x-0' : 'translate-x-full' }} bg-white flex flex-col shadow-2xl">

                    <div class="px-6 py-5 flex items-center justify-between border-b border-neutral-100">
                        <h3 class="font-display text-2xl text-neutral-900">Tu Selección</h3>
                        <button wire:click="$set('showCartDrawer', false)" class="text-neutral-400 hover:text-neutral-900 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5">
                        @forelse($cartItems as $item)
                            <div class="flex gap-4 pb-5 border-b border-neutral-100 relative">
                                <div class="w-20 h-24 bg-neutral-50 flex-shrink-0 overflow-hidden">
                                    @if($item['combo']->imagen)
                                        <img src="{{ asset('images/combos/' . $item['combo']->imagen) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 pr-6">
                                    <span class="text-[9px] tracking-widest uppercase text-[#A98A4B]">{{ $item['combo']->categoria }}</span>
                                    <h4 class="font-display text-base text-neutral-900 leading-tight mt-0.5">{{ $item['combo']->nombre }}</h4>
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex items-center border border-neutral-200">
                                            <button wire:click="updateQuantity({{ $item['combo']->id }}, {{ $item['quantity'] - 1 }})" class="px-2.5 py-1 text-neutral-500 hover:text-neutral-900">−</button>
                                            <span class="px-3 py-1 text-sm text-neutral-800">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity({{ $item['combo']->id }}, {{ $item['quantity'] + 1 }})" class="px-2.5 py-1 text-neutral-500 hover:text-neutral-900">+</button>
                                        </div>
                                        <span class="font-display text-lg text-neutral-900">S/ {{ number_format($item['subtotal'], 2) }}</span>
                                    </div>
                                </div>
                                <button wire:click="removeFromCart({{ $item['combo']->id }})" class="absolute top-0 right-0 text-neutral-300 hover:text-neutral-700 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @empty
                            <div class="py-20 text-center">
                                <div class="font-display text-5xl text-neutral-200 mb-3">∅</div>
                                <p class="text-neutral-400 text-sm tracking-wide">Tu selección está vacía.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($cartCount > 0)
                        <div class="border-t border-neutral-100 px-6 py-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs tracking-widest uppercase text-neutral-500">Total</span>
                                <span class="font-display text-3xl text-neutral-900">S/ {{ number_format($cartTotal, 2) }}</span>
                            </div>
                            <button wire:click="openCheckoutForm" class="w-full py-4 bg-neutral-900 hover:bg-[#A98A4B] text-white text-[11px] tracking-widest uppercase transition-colors duration-300">
                                Finalizar Pedido
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================
         MODAL CHECKOUT
    =================================================================== --}}
    @if($showCheckoutForm)
        <div class="fixed inset-0 bg-neutral-900/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white max-w-lg w-full shadow-2xl">
                <div class="px-6 py-5 flex items-center justify-between border-b border-neutral-100">
                    <h3 class="font-display text-2xl text-neutral-900">Confirmar Pedido</h3>
                    <button wire:click="$set('showCheckoutForm', false)" class="text-neutral-400 hover:text-neutral-900 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="bg-neutral-50 p-5">
                        <h4 class="text-[10px] tracking-widest uppercase text-neutral-400 mb-3">Resumen</h4>
                        <div class="space-y-2 text-sm text-neutral-600">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between">
                                    <span class="truncate pr-3">{{ $item['combo']->nombre }} × {{ $item['quantity'] }}</span>
                                    <span class="font-display text-neutral-900">S/ {{ number_format($item['subtotal'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-neutral-200 mt-4 pt-4 flex justify-between items-baseline">
                            <span class="text-xs tracking-widest uppercase text-neutral-500">Total</span>
                            <span class="font-display text-2xl text-neutral-900">S/ {{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>

                    <form wire:submit.prevent="confirmCheckout" class="space-y-5">
                        <div>
                            <label class="block text-[10px] tracking-widest uppercase text-neutral-500 mb-2">Nombre Completo *</label>
                            <input type="text" wire:model="customerName" placeholder="Juan Pérez García"
                                class="w-full border-0 border-b border-neutral-300 bg-transparent px-1 py-2 text-sm text-neutral-800 focus:outline-none focus:border-neutral-900 transition {{ $errors->has('customerName') ? 'border-red-400' : '' }}">
                            @error('customerName') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] tracking-widest uppercase text-neutral-500 mb-2">Teléfono / WhatsApp *</label>
                            <input type="text" wire:model="customerPhone" placeholder="987 654 321"
                                class="w-full border-0 border-b border-neutral-300 bg-transparent px-1 py-2 text-sm text-neutral-800 focus:outline-none focus:border-neutral-900 transition {{ $errors->has('customerPhone') ? 'border-red-400' : '' }}">
                            @error('customerPhone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] tracking-widest uppercase text-neutral-500 mb-2">Email (opcional)</label>
                            <input type="email" wire:model="customerEmail" placeholder="correo@ejemplo.com"
                                class="w-full border-0 border-b border-neutral-300 bg-transparent px-1 py-2 text-sm text-neutral-800 focus:outline-none focus:border-neutral-900 transition {{ $errors->has('customerEmail') ? 'border-red-400' : '' }}">
                            @error('customerEmail') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="$set('showCheckoutForm', false)" class="flex-1 py-3 border border-neutral-300 text-neutral-600 hover:border-neutral-900 hover:text-neutral-900 text-[11px] tracking-widest uppercase transition">
                                Cancelar
                            </button>
                            <button type="submit" class="flex-1 py-3 bg-neutral-900 hover:bg-[#A98A4B] text-white text-[11px] tracking-widest uppercase transition-colors duration-300">
                                Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
