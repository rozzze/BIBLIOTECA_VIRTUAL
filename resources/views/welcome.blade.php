<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca Virtual</title>
    <link rel="icon" href="/favicon.ico" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-900 text-white min-h-screen flex flex-col justify-center items-center font-sans">

    {{-- Header superior con login/register --}}
    <header class="absolute top-0 left-0 w-full flex justify-end p-6 space-x-3">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/redirect-after-login') }}" 
                   class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Ir a mi panel
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 border border-zinc-600 rounded-lg hover:bg-zinc-700 transition">
                    Iniciar sesión
                </a>

            @endauth
        @endif
    </header>

    {{-- Contenido central --}}
    <main class="flex flex-col md:flex-row items-center justify-center gap-12 text-center md:text-left">
        <div class="max-w-lg">
            <h1 class="text-5xl font-extrabold mb-4">📚 Biblioteca Virtual</h1>
            <p class="text-zinc-400 text-lg leading-relaxed">
                Consulta, reserva y administra libros de forma rápida y segura.
                Un sistema moderno con roles de <strong>Alumno</strong>, 
                <strong>Bibliotecario</strong> y <strong>Administrador</strong>.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="{{ route('login') }}" 
                   class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                   Iniciar sesión
                </a>
                <a href="#catalog-preview" 
                   class="px-6 py-3 bg-zinc-800 border border-zinc-600 text-white rounded-lg hover:bg-zinc-700 transition">
                   Ver catálogo
                </a>
            </div>
        </div>

        <div class="relative">
            <img src="https://picsum.photos/seed/bookshelf/500/350" alt="Biblioteca" 
                 class="rounded-2xl shadow-2xl border border-zinc-700">
            <span class="absolute bottom-3 right-4 text-xs text-zinc-500">Demo visual</span>
        </div>
    </main>

    {{-- Sección inferior tipo preview --}}
    <section id="catalog-preview" class="w-full mt-16 border-t border-zinc-800 pt-12 text-center">
        <h2 class="text-3xl font-semibold mb-6">Explora nuestro catálogo</h2>
        <div class="flex justify-center gap-4 flex-wrap px-6">
            <img src="https://picsum.photos/seed/book1/120/160" class="rounded-lg" alt="Libro 1">
            <img src="https://picsum.photos/seed/book2/120/160" class="rounded-lg" alt="Libro 2">
            <img src="https://picsum.photos/seed/book3/120/160" class="rounded-lg" alt="Libro 3">
            <img src="https://picsum.photos/seed/book4/120/160" class="rounded-lg" alt="Libro 4">
        </div>
        <p class="text-zinc-400 mt-4">Inicia sesión para ver todos los títulos disponibles 📖</p>
    </section>

    {{-- Footer --}}
    <footer class="mt-20 text-sm text-zinc-600 pb-6">
        &copy; {{ date('Y') }} Biblioteca Virtual — Desarrollado con ❤️ en Laravel 12 + Livewire
    </footer>
</body>
</html>
