<div class="container mx-auto p-6 max-w-5xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.careers.index') }}" 
               wire:navigate
               class="btn btn-circle btn-ghost">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Carrera
                </h1>
                <p class="text-amber-100 mt-2">Modifica la información de: <span class="font-bold">{{ $career->name }}</span></p>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <form wire:submit.prevent="save">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Detalles de la Carrera
                    <div class="badge badge-warning gap-2">ID: {{ $career->id }}</div>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre --}}
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre de la Carrera <span class="text-error">*</span></span>
                        </label>
                        {{-- ✅ CORREGIDO: Ahora usa wire:model="name" --}}
                        <input type="text" 
                               wire:model="name" 
                               placeholder="Ej: Ingeniería de Sistemas" 
                               class="input input-bordered input-warning @error('name') input-error @enderror" />
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    {{-- Abreviatura --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Abreviatura</span>
                        </label>
                        {{-- ✅ CORREGIDO: Ahora usa wire:model="abbreviation" --}}
                        <input type="text" 
                               wire:model="abbreviation" 
                               placeholder="Ej: IS" 
                               class="input input-bordered input-warning @error('abbreviation') input-error @enderror" />
                        @error('abbreviation')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="form-control mt-6">
                    <label class="label">
                        <span class="label-text font-semibold">Descripción</span>
                    </label>
                    {{-- ✅ CORREGIDO: Ahora usa wire:model="description" --}}
                    <textarea wire:model="description" 
                              class="textarea textarea-bordered textarea-warning @error('description') textarea-error @enderror" 
                              placeholder="Descripción breve de la carrera..." 
                              rows="3"></textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Info del sistema --}}
                <div class="alert alert-info shadow-lg mt-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-bold">Información del Registro</h3>
                        <div class="text-xs">
                            <p>Creado: {{ $career->created_at->format('d/m/Y H:i') }}</p>
                            <p>Última actualización: {{ $career->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-end mt-8">
                    <a href="{{ route('admin.careers.index') }}" 
                       wire:navigate 
                       class="btn btn-outline btn-lg gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning btn-lg gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>