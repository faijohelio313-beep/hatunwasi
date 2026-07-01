@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }} class="flex items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-8 w-auto object-contain">
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }} class="flex items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Hatun Wasi" class="h-8 w-auto object-contain">
    </flux:brand>
@endif
