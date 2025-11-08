<div class="container mx-auto p-6 max-w-6xl">
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">👩‍💼 Gestión de Autores</h1>
                <p class="text-indigo-100">Administra los autores registrados en el sistema</p>
            </div>
            <a href="{{ route('admin.authors.create') }}" wire:navigate class="btn btn-light">
                ➕ Nuevo Autor
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="form-control mb-4">
                <input type="text" wire:model.live="search" placeholder="Buscar autor..." class="input input-bordered w-full" />
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200 text-sm">
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($authors as $index => $author)
                            <tr class="hover">
                                <td>{{ $authors->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ $author->photo_url }}" class="w-14 h-14 rounded-full object-cover border" />
                                </td>
                                <td class="font-semibold">{{ $author->name }}</td>
                                <td>{{ $author->nationality ?? '—' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.authors.edit', $author->id) }}" wire:navigate class="btn btn-sm btn-info">Editar</a>
                                    <button wire:click="delete({{ $author->id }})" class="btn btn-sm btn-error">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-6 text-gray-500">No se encontraron autores</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $authors->links() }}</div>
        </div>
    </div>
</div>
