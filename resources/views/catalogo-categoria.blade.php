@php
    /**
     * Genera un swatch de material (CSS) a partir del color y tipo del producto,
     * al estilo de los muestrarios de Porcelanosa / Salvatori.
     */
    function swatchStyle(?string $color, ?string $tipo): string {
        $c = mb_strtolower(($color ?? '') . ' ' . ($tipo ?? ''));

        $palettes = [
            ['k' => ['dorado', 'gold', 'caramel', 'honey', 'miel'],          'g' => 'linear-gradient(135deg,#c9a961 0%,#e8d5a3 45%,#b08d45 100%)'],
            ['k' => ['negro', 'grafito', 'carbón', 'black'],                 'g' => 'linear-gradient(135deg,#1c1c1e 0%,#3a3a3e 55%,#111113 100%)'],
            ['k' => ['plata', 'plateado', 'silver', 'perla'],                'g' => 'linear-gradient(135deg,#d9dadb 0%,#f2f3f4 45%,#b8babd 100%)'],
            ['k' => ['gris', 'grey', 'gray', 'cemento', 'concreto', 'urbano'],'g' => 'linear-gradient(135deg,#9a9a98 0%,#c4c4c1 50%,#7d7d7b 100%)'],
            ['k' => ['blanco', 'marfil', 'hueso', 'nieve', 'nevada', 'ártico', 'lyon', 'nano'], 'g' => 'linear-gradient(135deg,#f4f2ed 0%,#ffffff 50%,#e5e2da 100%)'],
            ['k' => ['beige', 'crema', 'arena', 'jersey'],                   'g' => 'linear-gradient(135deg,#d9c9ab 0%,#eee3cd 50%,#c2ae8a 100%)'],
            ['k' => ['madera', 'café', 'cafe', 'castaño', 'caramelo', 'marrón', 'marron', 'parota', 'terracota', 'canela', 'cedro', 'haya', 'tigrillo', 'pino'], 'g' => 'linear-gradient(135deg,#8a6644 0%,#b08d63 48%,#6d4e31 100%)'],
            ['k' => ['azul', 'blue'],                                        'g' => 'linear-gradient(135deg,#37506e 0%,#5b7998 50%,#243a52 100%)'],
            ['k' => ['verde', 'esmeralda'],                                  'g' => 'linear-gradient(135deg,#3e5c4c 0%,#5d8871 50%,#2c4436 100%)'],
            ['k' => ['mix', 'multicolor', 'varios', 'decorativ'],            'g' => 'linear-gradient(135deg,#8a6644 0%,#9a9a98 35%,#d9c9ab 65%,#3a3a3e 100%)'],
        ];

        $bg = 'linear-gradient(135deg,#cfc9bd 0%,#e9e4d8 50%,#b5ad9c 100%)'; // piedra neutra
        foreach ($palettes as $p) {
            foreach ($p['k'] as $kw) {
                if (str_contains($c, $kw)) { $bg = $p['g']; break 2; }
            }
        }
        return $bg;
    }

    function swatchVeins(?string $color, ?string $tipo, ?string $nombre): string {
        $c = mb_strtolower(($color ?? '') . ' ' . ($tipo ?? '') . ' ' . ($nombre ?? ''));
        if (str_contains($c, 'mármol') || str_contains($c, 'marmol') || str_contains($c, 'calacatta') || str_contains($c, 'onyx')) {
            return 'background-image: linear-gradient(115deg, transparent 42%, rgba(255,255,255,.55) 45%, transparent 49%, transparent 68%, rgba(255,255,255,.35) 71%, transparent 75%);';
        }
        if (str_contains($c, 'madera') || str_contains($c, 'tablón') || str_contains($c, 'tablon') || str_contains($c, 'listón') || str_contains($c, 'liston')) {
            return 'background-image: repeating-linear-gradient(90deg, rgba(0,0,0,.07) 0 2px, transparent 2px 14px);';
        }
        if (str_contains($c, 'texturado') || str_contains($c, 'texturizado') || str_contains($c, 'estructurado') || str_contains($c, 'relieve')) {
            return 'background-image: repeating-linear-gradient(45deg, rgba(0,0,0,.05) 0 3px, transparent 3px 9px);';
        }
        return '';
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $nombre }} — Hatun Wasi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Cormorant Garamond', Georgia, serif; }
        body { font-family: 'Jost', ui-sans-serif, system-ui, sans-serif; }
        .tracking-luxe { letter-spacing: 0.3em; }

        /* Hero con zoom lento — patrón de las casas de lujo */
        @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.07); } }
        .hero-img { animation: slowZoom 18s ease-out forwards; }

        /* Revelado de línea dorada bajo el nombre al pasar el mouse */
        .card-luxe .gold-line { width: 0; transition: width .5s cubic-bezier(.25,.8,.25,1); }
        .card-luxe:hover .gold-line { width: 3rem; }
        .card-luxe .swatch-inner { transition: transform 1.2s cubic-bezier(.25,.8,.25,1); }
        .card-luxe:hover .swatch-inner { transform: scale(1.06); }

        /* Entrada suave de las tarjetas */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
        .card-luxe { animation: fadeUp .7s ease-out both; }

        .filter-chip[data-active="1"] { color: #171717; border-color: #A98A4B; }
        .filter-chip[data-active="1"] span { background: #A98A4B; }
    </style>
</head>
<body class="antialiased bg-[#faf9f7] text-neutral-800 min-h-screen flex flex-col">

    {{-- Header translúcido fijo --}}
    <header class="sticky top-0 z-40 bg-[#faf9f7]/90 backdrop-blur border-b border-neutral-200/60">
        <div class="max-w-[1500px] mx-auto px-6 lg:px-10 py-4 flex items-center justify-between">
            <a href="{{ route('store') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-12 w-auto object-contain">
            </a>
            <nav class="flex items-center gap-5 md:gap-8 text-[10.5px] tracking-luxe uppercase text-neutral-500 overflow-x-auto whitespace-nowrap [scrollbar-width:none] [&::-webkit-scrollbar]:hidden mx-4">
                <a href="{{ route('catalogo.proximamente', 'revestimientos') }}" class="hover:text-[#A98A4B] transition-colors {{ $nombre === 'Revestimientos' ? 'text-[#A98A4B]' : '' }}">Revestimientos</a>
                <a href="{{ route('catalogo.proximamente', 'accesorios') }}" class="hover:text-[#A98A4B] transition-colors {{ $nombre === 'Accesorios' ? 'text-[#A98A4B]' : '' }}">Accesorios</a>
                <a href="{{ route('catalogo.proximamente', 'sanitarios') }}" class="hover:text-[#A98A4B] transition-colors {{ $nombre === 'Sanitarios' ? 'text-[#A98A4B]' : '' }}">Sanitarios</a>
                <a href="{{ route('catalogo.proximamente', 'ceramicos-y-componentes') }}" class="hover:text-[#A98A4B] transition-colors {{ $nombre === 'Cerámicos y Componentes' ? 'text-[#A98A4B]' : '' }}">Cerámicos</a>
            </nav>
            <a href="{{ route('store') }}" class="text-[10.5px] tracking-luxe uppercase text-neutral-500 hover:text-[#A98A4B] transition">
                &larr; Tienda
            </a>
        </div>
    </header>

    {{-- Hero editorial a sangre completa --}}
    <section class="relative h-[380px] sm:h-[460px] overflow-hidden bg-neutral-900">
        <img src="{{ asset('images/' . ($heroImg ?? 'hero.jpg')) }}" alt="{{ $nombre }}"
             class="hero-img absolute inset-0 w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/85 via-neutral-900/30 to-neutral-900/40"></div>
        <div class="relative h-full max-w-[1500px] mx-auto px-6 lg:px-10 flex flex-col justify-end pb-14">
            <nav class="text-[10px] tracking-luxe uppercase text-white/50 mb-5">
                <a href="{{ route('store') }}" class="hover:text-white/80 transition">Inicio</a>
                <span class="mx-2">/</span> Catálogo
                <span class="mx-2">/</span> <span class="text-[#d8c39a]">{{ $nombre }}</span>
            </nav>
            <h1 class="font-display text-6xl sm:text-7xl font-light text-white leading-none">{{ $nombre }}</h1>
        </div>
    </section>

    {{-- Intro editorial: cifra + descripción --}}
    <section class="max-w-[1500px] mx-auto w-full px-6 lg:px-10 py-14 grid grid-cols-1 md:grid-cols-12 gap-8 items-end">
        <div class="md:col-span-3 flex items-baseline gap-4">
            <span class="font-display text-7xl font-light text-[#A98A4B] leading-none">{{ $productos->count() }}</span>
            <span class="text-[10px] tracking-luxe uppercase text-neutral-400 pb-2">piezas en<br>colección</span>
        </div>
        <div class="md:col-span-6">
            <p class="font-display text-2xl font-light text-neutral-700 leading-relaxed">{{ $subtitulo }}</p>
        </div>
        <div class="md:col-span-3 md:text-right">
            <span class="text-[10px] tracking-luxe uppercase text-neutral-400">Precios referenciales<br>Stock a consultar en tienda</span>
        </div>
    </section>

    <div class="max-w-[1500px] mx-auto w-full px-6 lg:px-10">
        <hr class="border-neutral-200">
    </div>

    @php $tipos = $productos->pluck('tipo_producto')->filter()->unique()->sort()->values(); @endphp

    {{-- Filtros por tipo --}}
    @if($tipos->count() > 1)
        <div class="max-w-[1500px] mx-auto w-full px-6 lg:px-10 pt-10 flex flex-wrap items-center gap-x-7 gap-y-3">
            <button class="filter-chip text-[10.5px] tracking-luxe uppercase text-neutral-400 hover:text-neutral-900 transition pb-1 border-b border-transparent flex items-center gap-2" data-tipo="*" data-active="1">
                <span class="w-1 h-1 rounded-full bg-neutral-300"></span> Todo
            </button>
            @foreach($tipos as $t)
                <button class="filter-chip text-[10.5px] tracking-luxe uppercase text-neutral-400 hover:text-neutral-900 transition pb-1 border-b border-transparent flex items-center gap-2" data-tipo="{{ $t }}" data-active="0">
                    <span class="w-1 h-1 rounded-full bg-neutral-300"></span> {{ $t }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- Colección --}}
    <main class="max-w-[1500px] mx-auto w-full px-6 lg:px-10 py-12 flex-1">
        @if($productos->isEmpty())
            <div class="py-24 text-center">
                <div class="font-display text-5xl text-neutral-200 mb-3">∅</div>
                <p class="text-neutral-500 tracking-wide">Este catálogo aún no tiene productos cargados.</p>
            </div>
        @else
            <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-14">
                @foreach($productos as $i => $p)
                    <div class="card-luxe group cursor-default" data-tipo="{{ $p->tipo_producto }}" style="animation-delay: {{ ($i % 8) * 60 }}ms">
                        {{-- Swatch de material --}}
                        <div class="relative aspect-[4/3] overflow-hidden bg-neutral-100">
                            <div class="swatch-inner absolute inset-0" style="background: {{ swatchStyle($p->color, $p->tipo_producto) }}">
                                <div class="absolute inset-0" style="{{ swatchVeins($p->color, $p->tipo_producto, $p->nombre) }}"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-white/10"></div>
                            </div>
                            @if($p->codigo)
                                <span class="absolute top-4 right-4 text-[9px] font-mono tracking-wider text-white/85 bg-black/25 backdrop-blur-sm px-2.5 py-1">{{ $p->codigo }}</span>
                            @endif
                            @if($p->formato)
                                <span class="absolute bottom-4 left-4 text-[9.5px] tracking-luxe uppercase text-white/90">{{ $p->formato }}</span>
                            @endif
                        </div>

                        {{-- Ficha --}}
                        <div class="pt-5">
                            <div class="text-[9px] tracking-luxe uppercase text-[#A98A4B] mb-2">{{ $p->tipo_producto }}</div>
                            <h3 class="font-display text-[22px] font-medium text-neutral-900 leading-tight">{{ $p->nombre }}</h3>
                            <div class="gold-line h-px bg-[#A98A4B] mt-3"></div>

                            <div class="mt-4 space-y-1 text-[12px] text-neutral-500 font-light">
                                @if($p->color)<div>{{ $p->color }}</div>@endif
                                @if($p->marca && $p->marca !== 'Hatun Wasi')<div class="text-neutral-400">{{ $p->marca }}</div>@endif
                            </div>

                            @if($p->descripcion)
                                <p class="mt-3 text-[11.5px] text-neutral-400 font-light leading-relaxed line-clamp-2">{{ $p->descripcion }}</p>
                            @endif

                            {{-- Precio referencial --}}
                            <div class="mt-4 pt-3 border-t border-neutral-200/70 flex items-baseline justify-between">
                                @if($p->precio > 0)
                                    <div class="flex items-baseline gap-1 text-neutral-900">
                                        <span class="text-[11px] font-medium text-neutral-500">S/</span>
                                        <span class="text-[17px] font-semibold tracking-tight tabular-nums">{{ number_format($p->precio, 2) }}</span>
                                    </div>
                                    <span class="text-[9px] tracking-luxe uppercase text-neutral-400">Precio ref.</span>
                                @else
                                    <span class="text-[11px] tracking-luxe uppercase text-neutral-400">Precio a consultar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- Cinta de asesoría --}}
    <section class="bg-neutral-900 text-white">
        <div class="max-w-[1500px] mx-auto px-6 lg:px-10 py-16 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-8">
            <div>
                <span class="text-[10px] tracking-luxe uppercase text-[#d8c39a]">Asesoría personalizada</span>
                <h2 class="font-display text-4xl font-light mt-3">Visítanos y descubre la colección completa</h2>
                <p class="text-sm text-white/60 mt-3 font-light">Nuestros especialistas te ayudan a elegir el material perfecto para tu proyecto.</p>
            </div>
            <a href="{{ route('store') }}" class="flex-shrink-0 border border-[#A98A4B] text-[#d8c39a] hover:bg-[#A98A4B] hover:text-white px-10 py-4 text-[10.5px] tracking-luxe uppercase transition-colors duration-500">
                Ver Combos de Ambientes
            </a>
        </div>
    </section>

    <footer class="bg-neutral-950 text-neutral-500 py-8 text-center text-[10px] tracking-luxe uppercase">
        © {{ date('Y') }} Casas Cerámicos Hatun Wasi · Jr. Sandia 206, Juliaca, Perú
    </footer>

    <script>
        // Filtro por tipo de producto (solo vista, sin backend)
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                document.querySelectorAll('.filter-chip').forEach(c => c.dataset.active = '0');
                chip.dataset.active = '1';
                const tipo = chip.dataset.tipo;
                document.querySelectorAll('#grid .card-luxe').forEach(card => {
                    card.style.display = (tipo === '*' || card.dataset.tipo === tipo) ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>
