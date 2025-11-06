<div class="container mx-auto p-6 max-w-7xl">
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h1 class="text-3xl font-bold">Gestión de Carreras</h1>
                </div>
                <p class="text-indigo-100">Administra las carreras profesionales del sistema</p>
            </div>
            <a href="{{ route('admin.careers.create') }}" 
               wire:navigate
               class="btn btn-lg bg-white text-indigo-600 hover:bg-indigo-50 border-0 gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Carrera
            </a>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Búsqueda
            </h2>
            <div class="form-control max-w-md">
                <label class="label">
                    <span class="label-text font-semibold">Buscar Carrera</span>
                </label>
                <input type="text" 
                       wire:model.live="search"
                       placeholder="Buscar por nombre o abreviatura..."
                       class="input input-bordered input-primary w-full" />
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

    {{-- Careers Table --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-center">#</th>
                            <th>Carrera</th>
                            <th class="text-center">Abreviatura</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($careers as $career)
                            <tr class="hover">
                                <td class="text-center font-bold">{{ $career->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-indigo-500 text-white rounded-lg w-12 h-12">
                                                <span class="text-xl font-bold">{{ substr($career->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold text-base">{{ $career->name }}</div>
                                            <div class="text-sm opacity-50">ID: {{ $career->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($career->abbreviation)
                                        <div class="badge badge-primary badge-lg">{{ $career->abbreviation }}</div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="max-w-md">
                                        @if($career->description)
                                            <p class="text-sm line-clamp-2">{{ $career->description }}</p>
                                        @else
                                            <span class="text-gray-400">Sin descripción</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.careers.edit', $career->id) }}" 
                                           wire:navigate
                                           class="btn btn-sm btn-info gap-2 tooltip" data-tip="Editar carrera">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>

                                        <button wire:click="delete({{ $career->id }})"
                                                wire:confirm="¿Estás seguro de eliminar la carrera '{{ $career->name }}'?"
                                                class="btn btn-sm btn-error gap-2 tooltip" data-tip="Eliminar carrera">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-4">
                                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="text-xl font-semibold text-gray-500">No se encontraron carreras</div>
                                        <p class="text-gray-400">Crea tu primera carrera profesional</p>
                                        <a href="{{ route('admin.careers.create') }}" 
                                           wire:navigate
                                           class="btn btn-primary gap-2 mt-4">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Nueva Carrera
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

</div>

