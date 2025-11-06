<div class="container mx-auto p-6 max-w-5xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Carrera Profesional
                </h1>
                <p class="text-green-100 mt-2">Registra una nueva carrera en el sistema académico</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save">
        {{-- Información de la Carrera --}}
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Información de la Carrera
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre --}}
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre de la Carrera <span class="text-error">*</span></span>
                        </label>
                        <input type="text" 
                               wire:model="name"
                               placeholder="Ej: Ingeniería de Sistemas"
                               class="input input-bordered input-success @error('name') input-error @enderror" />
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
                            <span class="label-text-alt text-gray-500">Opcional</span>
                        </label>
                        <input type="text" 
                               wire:model="abbreviation"
                               placeholder="Ej: IS"
                               maxlength="15"
                               class="input input-bordered input-success @error('abbreviation') input-error @enderror" />
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
                        <span class="label-text-alt text-gray-500">Opcional</span>
                    </label>
                    <textarea wire:model="description" 
                              class="textarea textarea-bordered textarea-success h-32 @error('description') textarea-error @enderror" 
                              placeholder="Escribe una breve descripción de la carrera profesional..."></textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                    <label class="label">
                        <span class="label-text-alt"></span>
                        <span class="label-text-alt">Máximo 255 caracteres</span>
                    </label>
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info shadow-lg mt-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-bold">Información importante</h3>
                        <div class="text-sm">
                            <p>• El nombre de la carrera debe ser único en el sistema</p>
                            <p>• La abreviatura es útil para reportes y listados cortos</p>
                            <p>• Una buena descripción ayuda a identificar mejor la carrera</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vista Previa (Opcional) --}}
        @if($name)
            <div class="card bg-base-100 shadow-xl mb-6 border-2 border-success animate-fade-in">
                <div class="card-body">
                    <h3 class="card-title text-lg text-success">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Vista Previa
                    </h3>
                    <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                        <div class="avatar placeholder">
                            <div class="bg-success text-white rounded-lg w-16 h-16">
                                <span class="text-2xl font-bold">{{ substr($name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold">{{ $name }}</h4>
                            @if($abbreviation)
                                <div class="badge badge-success mt-1">{{ $abbreviation }}</div>
                            @endif
                            @if($description)
                                <p class="text-sm text-gray-600 mt-2">{{ $description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Botones de Acción --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.careers.index') }}" 
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
                        Crear Carrera
                    </button>
                </div>
            </div>
        </div>
    </form>

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

