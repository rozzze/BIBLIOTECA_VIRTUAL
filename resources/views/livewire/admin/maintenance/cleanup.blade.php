<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-xl">Mantenimiento del Sistema</h2>
        <p class="text-sm text-gray-500 mb-4">Gestión de archivos temporales.</p>

        {{-- Mensaje de éxito o error --}}
        @if (session()->has('success'))
            <div class="alert alert-success shadow-lg mb-4">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-error shadow-lg mb-4">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Alerta de Límite Excedido --}}
        @if ($isOverLimit)
            <div class="alert alert-warning shadow-lg mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>¡Atención! La carpeta temporal supera el límite de {{ $limitInMb }} MB.</span>
            </div>
        @endif

        {{-- Estadísticas y Botón --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="stats bg-base-200">
                <div class="stat">
                    <div class="stat-title">Uso de `livewire-tmp`</div>
                    @if($sizeInMb == -1)
                        <div class="stat-value text-error">Error</div>
                        <div class="stat-desc">No se pudo leer la carpeta</div>
                    @else
                        <div class="stat-value {{ $isOverLimit ? 'text-warning' : 'text-success' }}">
                            {{ $sizeInMb }} MB
                        </div>
                        <div class="stat-desc">Límite: {{ $limitInMb }} MB</div>
                    @endif
                </div>
            </div>

            <button 
                wire:click="clearTmpFolder"
                wire:confirm="¿Estás seguro?\n\nEsto borrará TODOS los archivos temporales de `livewire-tmp` (los que se están subiendo AHORA MISMO) y no solo los antiguos.\n\nEs recomendable hacerlo cuando nadie esté usando el sistema."
                class="btn btn-error"
                {{ $sizeInMb == 0 || $sizeInMb == -1 ? 'disabled' : '' }}
            >
                <span wire:loading.remove wire:target="clearTmpFolder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Vaciar Carpeta Temporal Ahora
                </span>
                <span wire:loading wire:target="clearTmpFolder" class="loading loading-spinner loading-sm"></span>
            </button>
        </div>

    </div>
</div>