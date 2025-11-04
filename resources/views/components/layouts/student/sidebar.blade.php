<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    {{-- 
        Aquí está el SIDEBAR fusionado.
        Usa la estructura 'collapsible' del ejemplo, pero con tus colores.
    --}}
    <flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        
        {{-- 1. HEADER DEL SIDEBAR (del ejemplo) --}}
        <flux:sidebar.header>
            
            {{-- Aquí va TU LOGO en lugar de <flux:sidebar.brand> --}}
            <a href="{{ auth()->check() ? route('catalog') : route('landing') }}" wire:navigate>
                <x-app-logo />
            </a>

            {{-- El botón para colapsar (del ejemplo) --}}
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        
        </flux:sidebar.header>

        {{-- 2. NAVEGACIÓN (Tus items en la estructura del ejemplo) --}}
        <flux:sidebar.nav>
            <flux:sidebar.group 
                expandable 
                icon="book-open" 
                heading="{{__('Biblioteca Virtual')}}" 
                class="grid"
            >
                @auth
                    <flux:sidebar.item 
                        :href="route('catalog')"
                        :current="request()->routeIs('catalog')"
                        wire:navigate>
                        {{ __('Catálogo de Libros') }}
                    </flux:sidebar.item>

                    {{-- ¡Añadí los enlaces basados en tu idea de app! --}}
                    <flux:sidebar.item 
                        icon="bell" 
                        href="#" {{-- :href="route('reminders')" --}}
                        {{-- :current="request()->routeIs('reminders')" --}}
                    >
                        {{ __('Mis Recordatorios') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item 
                        icon="beaker" 
                        href="#" {{-- :href="route('treatments')" --}}
                        {{-- :current="request()->routeIs('treatments')" --}}
                    >
                        {{ __('Mis Tratamientos') }}
                    </flux:sidebar.item>

                @else
                    {{-- Para usuarios no autenticados --}}
                    <flux:sidebar.item 
                        :href="route('landing')"
                        :current="request()->routeIs('landing')"
                        wire:navigate>
                        {{ __('Inicio') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item 
                        icon="arrow-left-on-rectangle"
                        :href="route('login')"
                        :current="request()->routeIs('login')"
                        wire:navigate>
                        {{ __('Iniciar Sesión') }}
                    </flux:sidebar.item>
                @endauth
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        {{-- 3. NAVEGACIÓN SECUNDARIA (del ejemplo, adaptada) --}}
        <flux:sidebar.nav>
            @auth
                {{-- AQUÍ ESTÁ EL ENLACE MODIFICADO --}}
                <flux:sidebar.item 
                    icon="key" {{-- Cambié el ícono a 'key' (llave) --}}
                    :href="route('student.password.edit')" {{-- Apunta a tu nueva ruta --}}
                    :current="request()->routeIs('student.password.edit')" {{-- Revisa la nueva ruta --}}
                    wire:navigate
                >
                    {{ __('Cambiar Contraseña') }}
                </flux:sidebar.item>
            @endauth
            <flux:sidebar.item icon="information-circle" href="#">{{ __('Ayuda') }}</flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- 4. PERFIL (al final del sidebar, para DESKTOP) --}}
        @auth
            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                {{-- Usamos 'sidebar.profile' del ejemplo para el trigger --}}
                <flux:sidebar.profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />

                {{-- Tu menú de logout --}}
                <flux:menu class="w-[220px]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:sidebar>

    {{-- 5. HEADER PARA MÓVIL (del ejemplo, adaptado) --}}
    <flux:header class="lg:hidden">
        {{-- Botón de hamburguesa para abrir el sidebar --}}
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        {{-- Tu perfil/login para MÓVIL --}}
        @auth
            <flux:dropdown position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
                <flux:menu class="w-[220px]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @else
            {{-- Botones de Login/Register para móvil --}}
            <flux:button :href="route('login')" variant="outline" wire:navigate>{{ __('Iniciar Sesión') }}</flux:button>
        @endauth

    </flux:header>


    {{-- Este es el slot que recibirá el <flux:main> --}}
    {{ $slot }} 
    
    @fluxScripts
</body>
</html>
