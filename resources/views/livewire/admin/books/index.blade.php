<div class="container mx-auto p-6 max-w-7xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-sky-600 to-cyan-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6l4 2M4 6h16M4 18h16" />
                    </svg>
                    <h1 class="text-3xl font-bold">Gestión de Libros</h1>
                </div>
                <p class="text-sky-100">Administra el catálogo de libros: crear, editar, filtrar y eliminar</p>
            </div>
            <a href="{{ route('admin.books.create') }}" wire:navigate
               class="btn btn-lg bg-white text-sky-700 hover:bg-sky-50 border-0 gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Libro
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl mb-2">Filtros</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Buscar</span></label>
                    <input type="text" placeholder="Título, autor, editorial..."
                           wire:model.live.debounce.500ms="search"
                           class="input input-bordered input-primary w-full"/>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Categoría</span></label>
                    <select wire:model.live="filterCategory" class="select select-bordered select-primary w-full">
                        <option value="">Todas</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Autor</span></label>
                    <select wire:model.live="filterAuthor" class="select select-bordered select-primary w-full">
                        <option value="">Todos</option>
                        @foreach ($authors as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Editorial</span></label>
                    <select wire:model.live="filterPublisher" class="select select-bordered select-primary w-full">
                        <option value="">Todas</option>
                        @foreach ($publishers as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" wire:click="clearFilters" class="btn btn-outline btn-sm">
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-center">Portada</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Categoría</th>
                            <th class="text-center">Año</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($books as $book)
                        <tr class="hover">
                            <td class="text-center">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-14 h-14">
                                        <img src="{{ $book->cover_url }}" alt="cover">
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold">
                                <div>{{ $book->title }}</div>
                                <div class="text-xs opacity-60">ID: {{ $book->id }}</div>
                            </td>
                            <td>{{ $book->author?->name ?? '—' }}</td>
                            <td>{{ $book->publisher?->name ?? '—' }}</td>
                            <td>
                                <div class="badge"
                                     style="background-color: {{ $book->category?->color ?? '#E5E7EB' }};
                                            color: #111827;">
                                    {{ $book->category?->name ?? '—' }}
                                </div>
                            </td>
                            <td class="text-center">{{ $book->publication_year ?: '—' }}</td>
                            <td class="text-center">
                                <div class="badge {{ $book->stock > 0 ? 'badge-success' : 'badge-error' }}">
                                    {{ $book->stock }}
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.books.edit', $book->id) }}" wire:navigate
                                       class="btn btn-sm btn-info gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                     a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    <button
                                        wire:click="deleteBook({{ $book->id }})"
                                        wire:confirm="¿Eliminar el libro «{{ $book->title }}»?"
                                        class="btn btn-sm btn-error gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                                     a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10">
                                <div class="text-gray-500">No hay libros para mostrar.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($books->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
