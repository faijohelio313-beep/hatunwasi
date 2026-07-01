<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hatun Wasi — Ambientes de Diseño</title>

    {{-- Tipografía elegante estilo boutique (serif para títulos) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .font-display { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-body    { font-family: 'Jost', ui-sans-serif, system-ui, sans-serif; }
        body          { font-family: 'Jost', ui-sans-serif, system-ui, sans-serif; }
        .tracking-luxe { letter-spacing: 0.25em; }
        .hero-fade { background: linear-gradient(180deg, rgba(28,25,23,0) 0%, rgba(28,25,23,0.55) 100%); }
    </style>
</head>
<body class="antialiased bg-white text-neutral-800">
    {{ $slot }}
    @livewireScripts
</body>
</html>
