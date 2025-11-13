<div class="container mx-auto p-6 max-w-5xl">
    {{-- Encabezado --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Importación Masiva de Libros</h1>
        <p class="text-green-100 mt-2">Sube un archivo Excel (.xlsx) o CSV para registrar libros.</p>
    </div>

    {{-- Formulario de Subida --}}
    {{-- 👇👇 ¡AQUÍ ESTÁ EL ARREGLO! Llama a 'save' 👇👇 --}}
    <form wire:submit.prevent="save" class="card bg-base-100 shadow-xl p-8">

        {{-- Mensajes de Éxito o Error --}}
        @if ($successMessage)
            <div class="alert alert-success shadow-lg mb-6">
                <span>{{ $successMessage }}</span>
            </div>
        @endif
        
        @if ($importError)
            <div class="alert alert-error shadow-lg mb-6">
                <span>Ocurrió un error inesperado: {{ $importError }}</span>
            </div>
        @endif

        {{-- Errores de Validación (Fila por Fila) --}}
        @if ($validationErrors && $validationErrors->isNotEmpty())
            <div class="alert alert-warning shadow-lg mb-6">
                <div class="flex flex-col">
                    <span class="font-semibold mb-2">Se encontraron errores en la importación:</span>
                    <ul class="list-disc pl-5">
                        @foreach ($validationErrors as $failure)
                            <li>
                                Fila {{ $failure->row() }}: {{ $failure->errors()[0] }}
                                (Valor: '{{ $failure->values()[$failure->attribute()] ?? 'N/A' }}')
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Paso 1: Encabezado (ya no es un botón) --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold">1. Prepara tu Archivo Excel (.xlsx)</h2>
            <p class="text-gray-600 dark:text-gray-300 mt-1">
                Asegúrate de que tu archivo tenga las columnas con los encabezados en minúscula:
                <code class="text-sm bg-base-200 p-1 rounded">titulo</code>,
                <code class="text-sm bg-base-200 p-1 rounded">autor</code>,
                <code class="text-sm bg-base-200 p-1 rounded">editorial</code>,
                <code class="text-sm bg-base-200 p-1 rounded">categoria</code>,
                <code class="text-sm bg-base-200 p-1 rounded">ano_publicacion</code>,
                <code class="text-sm bg-base-200 p-1 rounded">stock</code>, etc.
            </p>
        </div>

        {{-- Paso 2: Subir --}}
        <div class="form-control w-full">
            <label class="label"><span class="label-text font-semibold">2. Sube el Archivo</span></label>
            <input type="file" wire:model="file" class="file-input file-input-bordered file-input-primary w-full" />
            <div wire:loading wire:target="file" class="text-sm text-gray-500 mt-2">Cargando archivo...</div>
            @error('file') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>
        
        {{-- Botón de Enviar --}}
        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h1.94a2 2 0 110 4h-1.94A5.002 5.002 0 0117 15a4 4 0 11-8 0 4 4 0 01-.88-7.903A4 4 0 007 16z"></path></svg>
                    Importar Libros
                </span>
                <span wire:loading class="loading loading-spinner loading-md"></span>
            </button>
        </div>
    </form>
</div>