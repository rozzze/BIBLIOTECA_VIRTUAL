<div class="container mx-auto p-6 max-w-6xl">
    {{-- 🧭 Encabezado --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.books.index') }}" wire:navigate class="btn btn-circle btn-ghost">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold">Detalle del Libro</h1>
                    <p class="text-gray-300 mt-2">Ficha completa del título seleccionado.</p>
                </div>
            </div>
            <a href="{{ route('admin.books.edit', $book->slug) }}" wire:navigate class="btn btn-primary">
                Editar Libro
            </a>
        </div>
    </div>

    {{-- 📇 Ficha del Libro --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body p-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                {{-- Columna Izquierda: Portada y Estado --}}
                <div class="w-full md:w-1/3 flex-shrink-0">
                    <img src="{{ $book->cover_url }}" alt="Portada de {{ $book->title }}" 
                         class="w-full h-auto object-cover rounded-lg shadow-lg" />
                    
                    <div class="mt-6 space-y-2">
                        <div class="flex justify-between items-center">
                            <span classa="text-sm font-semibold text-gray-600">Estado:</span>
                            @if($book->status == 'Disponible')
                                <span class="badge badge-success badge-lg">{{ $book->status }}</span>
                            @elseif($book->status == 'Prestado')
                                <span class="badge badge-warning badge-lg">{{ $book->status }}</span>
                            @else
                                <span class="badge badge-error badge-lg">{{ $book->status }}</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span classa="text-sm font-semibold text-gray-600">Stock:</span>
                            <span class="badge badge-neutral badge-lg">{{ $book->stock }} Unidades</span>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Información --}}
                <div class="w-full md:w-2/3">
                    <h1 class="text-4xl font-bold text-primary-focus">{{ $book->title }}</h1>
                    <h2 class="text-2xl font-medium text-gray-500 mt-1">
                        por 
                        <a href="#" class="text-indigo-600 hover:underline">
                            {{ $book->author?->name ?? 'Autor Desconocido' }}
                        </a>
                    </h2>

                    {{-- Badges (inspirado en tu imagen) --}}
                    <div class="flex flex-wrap gap-2 mt-4 border-y py-4">
                        <span class="badge badge-outline badge-primary">
                            Categoría: {{ $book->category?->name ?? 'N/A' }}
                        </span>
                        <span class="badge badge-outline badge-secondary">
                            Editorial: {{ $book->publisher?->name ?? 'N/A' }}
                        </span>
                        <span class="badge badge-outline">
                            Idioma: {{ $book->language ?? 'N/A' }}
                        </span>
                    </div>

                    {{-- Rating (de la inspiración) --}}
                    <div class="mt-4">
                        <div class="rating rating-md">
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled />
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled checked />
                            <span class="ml-2 text-lg font-semibold text-gray-600">(Admin Rating)</span>
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold mb-2">Resumen</h3>
                        <div class="prose max-w-none text-gray-700">
                            {{ $book->summary ?? 'No hay resumen disponible.' }}
                        </div>
                    </div>

                    {{-- Ficha Técnica --}}
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold mb-2">Ficha Técnica</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <tbody>
                                    <tr>
                                        <th class="w-1/3">Año de Publicación</th>
                                        <td>{{ $book->publication_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>N° de Páginas</th>
                                        <td>{{ $book->pages ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug (URL)</th>
                                        <td><code class="text-sm">{{ $book->slug }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>