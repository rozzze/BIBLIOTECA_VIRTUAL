<div class="container mx-auto p-6 max-w-5xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" wire:navigate class="btn btn-circle btn-ghost">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Usuario
                </h1>
                <p class="text-amber-100 mt-2">Modifica la información del usuario <b>{{ $name }}</b></p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="update">
        {{-- Información Básica --}}
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Información Básica
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre --}}
                    <x-input label="Nombre Completo" wire:model="name" icon="user" required />

                    {{-- Rol --}}
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Rol</span></label>
                        <select wire:model.live="role" class="select select-bordered select-warning">
                            <option value="">Seleccionar rol...</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Email --}}
                    <x-input label="Correo Electrónico" wire:model="email" icon="envelope" required />
                    
                    {{-- Password --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nueva Contraseña</span>
                            <span class="label-text-alt text-info">Dejar en blanco para mantener actual</span>
                        </label>
                        <input type="password" wire:model="password" class="input input-bordered input-warning" placeholder="Mínimo 6 caracteres" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del Alumno --}}
        @if($role === 'Alumno')
        <div class="card bg-base-100 shadow-xl mb-6 animate-fade-in">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Perfil de Alumno
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="DNI" wire:model="dni" icon="identification" required />
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Carrera</span></label>
                        <select wire:model="carrera_id" class="select select-bordered select-success">
                            <option value="">Seleccione carrera...</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}">{{ $career->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Turno</span></label>
                        <select wire:model="turno" class="select select-bordered select-success">
                            <option value="">Seleccione turno...</option>
                            <option value="Diurno">Diurno</option>
                            <option value="Nocturno">Nocturno</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Semestre</span></label>
                        <select wire:model="semestre" class="select select-bordered select-success">
                            <option value="">Seleccione semestre...</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <x-input label="Teléfono" wire:model="telefono" icon="phone" />
                    <x-input label="Dirección" wire:model="direccion" icon="map-pin" />
                </div>
            </div>
        </div>
        @endif

        {{-- Botones --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}" wire:navigate class="btn btn-outline btn-lg">Cancelar</a>
                <button type="submit" class="btn btn-warning btn-lg">Guardar Cambios</button>
            </div>
        </div>
    </form>

    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
    </style>
</div>
