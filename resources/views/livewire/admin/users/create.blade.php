<div class="container mx-auto p-6 max-w-5xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" 
               wire:navigate
               class="btn btn-circle btn-ghost">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Crear Nuevo Usuario
                </h1>
                <p class="text-green-100 mt-2">Completa el formulario para registrar un nuevo usuario en el sistema</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save">
        {{-- Información Básica --}}
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Información Básica
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre Completo <span class="text-error">*</span></span>
                        </label>
                        <input type="text" 
                               wire:model="name"
                               placeholder="Ej: Juan Pérez García"
                               class="input input-bordered input-primary @error('name') input-error @enderror" />
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Rol --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Rol del Usuario <span class="text-error">*</span></span>
                        </label>
                        <select wire:model.live="role" class="select select-bordered select-primary @error('role') select-error @enderror">
                            <option value="">Seleccionar rol...</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Correo Electrónico <span class="text-error">*</span></span>
                        </label>
                        <input type="email" 
                               wire:model="email"
                               placeholder="ejemplo@correo.com"
                               class="input input-bordered input-primary @error('email') input-error @enderror" />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Contraseña <span class="text-error">*</span></span>
                        </label>
                        <input type="password" 
                               wire:model="password"
                               placeholder="Mínimo 6 caracteres"
                               class="input input-bordered input-primary @error('password') input-error @enderror" />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Información de Alumno (Condicional) --}}
        @if($role === 'Alumno')
            <div class="card bg-base-100 shadow-xl mb-6 animate-fade-in">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Información Académica
                        <div class="badge badge-success gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Perfil de Alumno
                        </div>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- DNI --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">DNI <span class="text-error">*</span></span>
                            </label>
                            <input type="text" wire:model="dni" placeholder="12345678"
                                   class="input input-bordered input-success @error('dni') input-error @enderror" />
                            @error('dni') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Carrera --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Carrera <span class="text-error">*</span></span>
                            </label>
                            <select wire:model="carrera_id" class="select select-bordered select-success @error('carrera_id') select-error @enderror">
                                <option value="">Seleccione carrera...</option>
                                @foreach ($careers as $career)
                                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Turno --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Turno <span class="text-error">*</span></span>
                            </label>
                            <select wire:model="turno" class="select select-bordered select-success @error('turno') select-error @enderror">
                                <option value="">Seleccione turno...</option>
                                <option value="Diurno">Diurno</option>
                                <option value="Nocturno">Nocturno</option>
                            </select>
                            @error('turno') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Semestre --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Semestre <span class="text-error">*</span></span>
                            </label>
                            <select wire:model="semestre" class="select select-bordered select-success @error('semestre') select-error @enderror">
                                <option value="">Seleccione semestre...</option>
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('semestre') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Teléfono</span>
                            </label>
                            <input type="text" wire:model="telefono" placeholder="987654321"
                                   class="input input-bordered input-success" />
                        </div>

                        {{-- Dirección --}}
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Dirección</span>
                            </label>
                            <input type="text" wire:model="direccion" placeholder="Av. Principal 123"
                                   class="input input-bordered input-success" />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Botones --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.users.index') }}" 
                       wire:navigate
                       class="btn btn-outline btn-lg gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-lg gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear Usuario
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Animación --}}
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
