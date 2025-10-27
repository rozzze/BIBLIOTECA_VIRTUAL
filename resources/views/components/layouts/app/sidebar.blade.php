<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <!-- Sidebar -->
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <!-- Logo redirige según rol -->
        @auth
            @if (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Bibliotecario'))
                <a href="{{ route('admin.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                    <x-app-logo />
                </a>
            @elseif (auth()->user()->hasRole('Alumno'))
                <a href="{{ route('catalog') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                    <x-app-logo />
                </a>
            @endif
        @else
            <a href="{{ route('landing') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
        @endauth

        <!-- Navegación principal -->
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Biblioteca Virtual')" class="grid">
                @auth
                    <!-- Vista Alumno -->
                    @if (auth()->user()->hasRole('Alumno'))
                        <flux:navlist.item 
                            icon="book-open"
                            :href="route('catalog')"
                            :current="request()->routeIs('catalog')"
                            wire:navigate>
                            {{ __('Catálogo de Libros') }}
                        </flux:navlist.item>
                    @endif

                    <!-- Vista Admin / Bibliotecario -->
                    @if (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Bibliotecario'))
                        <flux:navlist.item 
                            icon="layout-grid"
                            :href="route('admin.dashboard')"
                            :current="request()->routeIs('admin.dashboard')"
                            wire:navigate>
                            {{ __('Panel Administrativo') }}
                        </flux:navlist.item>
                    @endif

                    <!-- Solo Administrador -->
                    @if (auth()->user()->hasRole('Administrador'))
                        <flux:navlist.item
                            icon="arrow-up-tray"
                            :href="route('admin.import-users')"
                            :current="request()->routeIs('admin.import-users')"
                            wire:navigate>
                            {{ __('Importar Usuarios') }}
                        </flux:navlist.item>
                    @endif
                @endauth
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Menú de usuario -->
        @auth
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <!-- Datos de usuario -->
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <flux:menu.separator />

                    <!-- Opciones de configuración -->
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>
                            {{ __('Perfil') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('password.edit')" icon="key" wire:navigate>
                            {{ __('Contraseña') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('appearance.edit')" icon="swatch" wire:navigate>
                            {{ __('Apariencia') }}
                        </flux:menu.item>
                        @if (Route::has('two-factor.show'))
                            <flux:menu.item :href="route('two-factor.show')" icon="shield-check" wire:navigate>
                                {{ __('Autenticación en dos pasos') }}
                            </flux:menu.item>
                        @endif
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <!-- Cerrar sesión -->
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

    <!-- Header móvil -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />

        @auth
            <flux:dropdown position="top" align="end">
                <flux:profile 
                    :initials="auth()->user()->initials()" 
                    icon-trailing="chevron-down" 
                />
                <flux:menu>
                    <!-- Versión móvil: solo cerrar sesión -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:header>

    {{ $slot }}
    @fluxScripts
</body>
</html>
