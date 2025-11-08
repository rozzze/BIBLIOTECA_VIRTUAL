<div class="container mx-auto p-6 max-w-6xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    🏷️ Gestión de Categorías
                </h1>
                <p class="text-blue-100 mt-2">Administra las categorías disponibles para los libros del catálogo</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" 
               wire:navigate
               class="btn btn-lg bg-white text-blue-600 hover:bg-blue-50 border-0 gap-2 shadow-lg">
                ➕ Nueva Categoría
            </a>
        </div>
    </div>

    {{-- Search --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Buscar Categoría</span>
                    </label>
                    <input type="text" 
                           wire:model.live.debounce.500ms="search"
                           placeholder="Ej: Ciencia, Historia..."
                           class="input input-bordered input-primary w-full" />
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Categories Table --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Color</th>
                        <th>Slug</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="font-semibold">{{ $categories->firstItem() + $index }}</td>
                            <td class="font-bold">{{ $category->name }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full border shadow-sm" 
                                        style="background-color: {{ $category->color ?? '#ccc' }}"></div>
                                    <span class="text-sm">{{ $category->color ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="text-sm text-gray-500">{{ $category->slug }}</td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       wire:navigate
                                       class="btn btn-sm btn-info">
                                        ✏️ Editar
                                    </a>
                                    <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="¿Seguro deseas eliminar '{{ $category->name }}'?"
                                            class="btn btn-sm btn-error">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                No se encontraron categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
