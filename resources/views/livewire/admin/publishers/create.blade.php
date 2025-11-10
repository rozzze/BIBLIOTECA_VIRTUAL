<div class="container mx-auto p-6 max-w-4xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold flex items-center gap-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nueva Editorial
        </h1>
        <p class="text-blue-100 mt-2">Registra una nueva editorial en el sistema</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Nombre --}}
        <div class="form-control">
            <label class="label font-semibold">Nombre *</label>
            <input type="text" wire:model="name" class="input input-bordered input-primary" placeholder="Ej: Penguin Random House">
            @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- País y Sitio web --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label font-semibold">País</label>
                <input type="text" wire:model="country" class="input input-bordered" placeholder="Ej: Reino Unido">
            </div>

            <div class="form-control">
                <label class="label font-semibold">Sitio web</label>
                <input type="url" wire:model="website" class="input input-bordered" placeholder="https://...">
                @error('website') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Descripción --}}
        <div class="form-control">
            <label class="label font-semibold">Descripción</label>
            <textarea wire:model="description" class="textarea textarea-bordered h-28" placeholder="Descripción breve de la editorial..."></textarea>
            @error('description') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Imagen --}}
        <div class="form-control">
            <label class="label font-semibold">Logo / Imagen</label>
            <input type="file" wire:model="logo" class="file-input file-input-bordered w-full max-w-xs" accept="image/*">
            @error('logo') <span class="text-error text-sm">{{ $message }}</span> @enderror

            @if ($logo)
                <div class="mt-4">
                    <p class="text-sm text-zinc-500 mb-2">Vista previa:</p>
                    <img src="{{ $logo->temporaryUrl() }}" class="w-32 h-32 rounded-lg border object-contain shadow-md" />
                </div>
            @endif
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.publishers.index') }}" wire:navigate class="btn btn-outline">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>
    </form>
</div>
