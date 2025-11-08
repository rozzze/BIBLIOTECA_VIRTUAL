<div class="container mx-auto p-6 max-w-4xl">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">✏️ Editar Categoría</h1>
        <p class="text-amber-100 mt-2">Modifica los datos de la categoría seleccionada.</p>
    </div>

    <form wire:submit.prevent="update">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body space-y-4">
                {{-- Nombre --}}
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nombre</span></label>
                    <input type="text" wire:model="name" class="input input-bordered input-warning">
                    @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Descripción --}}
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Descripción</span></label>
                    <textarea wire:model="description" class="textarea textarea-bordered"></textarea>
                </div>

                {{-- Color con vista previa --}}
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Color</span></label>
                    <div class="flex items-center gap-4">
                        <input type="color" wire:model="color" class="w-16 h-10 rounded-md border shadow-sm cursor-pointer">

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full border shadow" 
                                 style="background-color: {{ $color ?? '#ccc' }}"></div>
                            <span class="text-sm text-gray-600">{{ $color ?? '#ccc' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('admin.categories.index') }}" wire:navigate class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
    </form>
</div>
