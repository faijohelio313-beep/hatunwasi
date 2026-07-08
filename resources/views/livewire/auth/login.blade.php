<x-layouts::auth :title="'Iniciar Sesión'">
    <div class="flex flex-col gap-6">

        {{-- Encabezado --}}
        <div class="text-center">
            <span class="text-[10px] tracking-luxe uppercase text-[#A98A4B]">Hatun Wasi · Administración</span>
            <h1 class="font-display text-4xl text-neutral-900 mt-3">Bienvenido de nuevo</h1>
            <p class="text-sm text-neutral-500 mt-2 font-light">Ingresa tus credenciales para acceder al panel</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Correo -->
            <flux:input
                name="email"
                label="Correo electrónico"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="correo@hatunwasi.pe"
            />

            <!-- Contraseña -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="Contraseña"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Tu contraseña"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        ¿La olvidaste?
                    </flux:link>
                @endif
            </div>

            <!-- Recordar sesión -->
            <flux:checkbox name="remember" label="Mantener mi sesión iniciada" :checked="old('remember')" />

            <button type="submit" data-test="login-button"
                class="w-full py-3.5 bg-neutral-900 hover:bg-[#A98A4B] text-white text-[11px] tracking-luxe uppercase font-semibold transition-colors duration-500 cursor-pointer">
                Ingresar al Panel
            </button>
        </form>

        <div class="text-center space-y-3">
            <p class="text-[10px] tracking-luxe uppercase text-neutral-400">
                Acceso exclusivo para administradores
            </p>
            <a href="{{ route('store') }}" wire:navigate
               class="inline-block text-xs text-neutral-500 hover:text-[#A98A4B] transition-colors">
                &larr; Volver a la tienda
            </a>
        </div>
    </div>
</x-layouts::auth>
