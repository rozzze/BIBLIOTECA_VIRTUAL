<div class="container mx-auto p-6 max-w-7xl">
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <h1 class="text-3xl font-bold">Gestión de Usuarios</h1>
                </div>
                <p class="text-blue-100">Administra todos los usuarios del sistema de manera eficiente</p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               wire:navigate
               class="btn btn-lg bg-white text-blue-600 hover:bg-blue-50 border-0 gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Usuario
            </a>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtros de Búsqueda
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Buscar Usuario</span>
                    </label>
                    <input type="text" 
                           wire:model.live.debounce.500ms="search"
                           placeholder="Nombre o correo electrónico..."
                           class="input input-bordered input-primary w-full" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Filtrar por Rol</span>
                    </label>
                    <select wire:model.live="filterRole" class="select select-bordered select-primary w-full">
                        <option value="">Todos los roles</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Resultados</span>
                    </label>
                    {{-- Mostramos el total de usuarios (viene de $users->total()) --}}
                    @if(isset($users))
                        <div class="flex items-center h-12 px-4 bg-base-200 rounded-lg">
                            <span class="text-lg font-bold text-primary">{{ $users->total() }}</span>
                            <span class="ml-2 text-sm">usuarios encontrados</span>
                        </div>
                    @else
                         <div class="flex items-center h-12 px-4 bg-base-200 rounded-lg">
                            <span class="text-lg font-bold text-primary">0</span>
                            <span class="ml-2 text-sm">usuarios encontrados</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mb-6 animate-fade-in">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-lg mb-6 animate-fade-in">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Users Table --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-center">#</th>
                            <th>Usuario</th>
                            <th>Correo Electrónico</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($users) && $users->count() > 0)
                            @foreach ($users as $index => $user)
                                <tr class="hover">
                                    <td class="text-center font-bold">{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary text-primary-content rounded-full w-12">
                                                    {{-- Usamos la función initials() que definimos en el Modelo User --}}
                                                    <span class="text-xl">{{ $user->initials() }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-base">{{ $user->name }}</div>
                                                <div class="text-sm opacity-50">ID: {{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $roleName = $user->roles->first()?->name ?? 'Sin rol';
                                            $badgeClass = match($roleName) {
                                                'Administrador' => 'badge-error',
                                                'Bibliotecario' => 'badge-warning',
                                                'Alumno' => 'badge-success',
                                                default => 'badge-ghost'
                                            };
                                        @endphp
                                        <div class="badge {{ $badgeClass }} badge-lg gap-2">
                                            @if($roleName === 'Administrador')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            @elseif($roleName === 'Bibliotecario')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            @endif
                                            {{ $roleName }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               wire:navigate
                                               class="btn btn-sm btn-info gap-2 tooltip" data-tip="Editar usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            <button wire:click="deleteUser({{ $user->id }})"
                                                    wire:confirm="¿Estás seguro de eliminar a {{ $user->name }}?"
                                                    class="btn btn-sm btn-error gap-2 tooltip" data-tip="Eliminar usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-4">
                                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <div class="text-xl font-semibold text-gray-500">No se encontraron usuarios</div>
                                        <p class="text-gray-400">Intenta ajustar los filtros de búsqueda o crea uno nuevo.</p>
                                    </div>
                                </td>
                            @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($users) && $users->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ARREGLADO: El <style> ahora está DENTRO del <div> raíz --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</div>