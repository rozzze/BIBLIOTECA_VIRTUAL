<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        @auth
            <a href="{{ route('catalog') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
        @else
            <a href="{{ route('landing') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
        @endauth

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Biblioteca Virtual')" class="grid">
                @auth
                    <flux:navlist.item 
                        icon="book-open"
                        :href="route('catalog')"
                        :current="request()->routeIs('catalog')"
                        wire:navigate>
                        {{ __('Catálogo de Libros') }}
                    </flux:navlist.item>
                @endauth
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        @auth
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
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
        @endauth
    </flux:sidebar>

    <flux:header class="lg:hidden">
        </flux:header>

    {{-- Este es el slot que recibirá el <flux:main> --}}
    {{ $slot }} 
    
    @fluxScripts
</body>
</html>