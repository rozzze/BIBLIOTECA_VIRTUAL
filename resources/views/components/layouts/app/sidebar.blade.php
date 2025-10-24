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
                    @if (auth()->user()->hasRole('Alumno'))
                        <flux:navlist.item 
                            icon="book"
                            :href="route('catalog')"
                            :current="request()->routeIs('catalog')"
                            wire:navigate>
                            {{ __('Catálogo de Libros') }}
                        </flux:navlist.item>
                    @endif

                    @if (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Bibliotecario'))
                        <flux:navlist.item 
                            icon="layout-grid"
                            :href="route('admin.dashboard')"
                            :current="request()->routeIs('admin.dashboard')"
                            wire:navigate>
                            {{ __('Panel Administrativo') }}
                        </flux:navlist.item>
                    @endif
                @endauth
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Enlaces externos -->
        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repositorio') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentación') }}
            </flux:navlist.item>
        </flux:navlist>

        <!-- Menú de usuario -->
        @auth
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.separator />

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
