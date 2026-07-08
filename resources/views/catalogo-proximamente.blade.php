<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $nombre }} — Hatun Wasi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Cormorant Garamond', Georgia, serif; }
        body { font-family: 'Jost', ui-sans-serif, system-ui, sans-serif; }
        .tracking-luxe { letter-spacing: 0.25em; }
    </style>
</head>
<body class="antialiased bg-white text-neutral-800 min-h-screen flex flex-col">

    <header class="border-b border-neutral-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-5 flex items-center justify-between">
            <a href="{{ route('store') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-16 w-auto object-contain">
            </a>
            <a href="{{ route('store') }}" class="text-[11px] tracking-widest uppercase text-neutral-500 hover:text-[#A98A4B] transition">
                &larr; Volver a la tienda
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-6">
        <div class="text-center max-w-lg">
            <span class="text-[11px] tracking-luxe uppercase text-[#A98A4B]">Módulo en desarrollo</span>
            <h1 class="font-display text-5xl text-neutral-900 mt-4 mb-5">{{ $nombre }}</h1>
            <p class="text-sm text-neutral-500 leading-relaxed">
                Este catálogo forma parte del proyecto integral Hatun Wasi y está siendo
                desarrollado por otro equipo del curso. Próximamente estará disponible aquí.
            </p>
            <div class="mt-8">
                <a href="{{ route('store') }}" class="inline-block bg-[#A98A4B] hover:bg-[#8a6f3a] text-white px-7 py-3 text-[11px] tracking-widest uppercase transition-colors duration-300">
                    Ver Combos disponibles
                </a>
            </div>
        </div>
    </main>

    <footer class="border-t border-neutral-100 py-5 text-center text-[11px] tracking-widest uppercase text-neutral-400">
        © {{ date('Y') }} Hatun Wasi · Proyecto Universitario
    </footer>

</body>
</html>
