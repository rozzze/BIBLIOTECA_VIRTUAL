<div class="container mx-auto p-6 max-w-4xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-600 to-orange-500 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold flex items-center gap-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar Editorial
        </h1>
        <p class="text-amber-100 mt-2">Modifica los datos de la editorial seleccionada</p>
    </div>

    <form wire:submit.prevent="update" class="space-y-6">
        {{-- Nombre --}}
        <div class="form-control">
            <label class="label font-semibold">Nombre *</label>
            <input type="text" wire:model="name" class="input input-bordered input-warning" placeholder="Ej: Penguin Random House">
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

            <div class="mt-4 flex flex-col md:flex-row items-center gap-6">
                @if ($logo)
                    <div>
                        <p class="text-sm text-zinc-500 mb-2">Nueva imagen:</p>
                        <img src="{{ $logo->temporaryUrl() }}" class="w-32 h-32 rounded-lg border object-contain shadow-md" />
                    </div>
                @endif

                @if ($publisher->logo_url)
                    <div>
                        <p class="text-sm text-zinc-500 mb-2">Actual:</p>
                        <img src="{{ $publisher->logo_url }}" class="w-32 h-32 rounded-lg border object-contain shadow-md" />
                    </div>
                @endif
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.publishers.index') }}" wire:navigate class="btn btn-outline">
                Cancelar
            </a>
            <button type="submit" class="btn btn-warning">
                Actualizar
            </button>
        </div>
    </form>
</div>
