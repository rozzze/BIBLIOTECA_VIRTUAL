<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">✏️ Editar Autor</h1>
        <p class="text-amber-100 mt-2">Modifica los datos del autor: <strong>{{ $author->name }}</strong></p>
    </div>

    <form wire:submit.prevent="update" class="space-y-6">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nombre *</span></label>
                    <input type="text" wire:model="name" class="input input-bordered input-warning" />
                    @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nacionalidad</span></label>
                    <input type="text" wire:model="nationality" class="input input-bordered" />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Biografía</span></label>
                    <textarea wire:model="biography" class="textarea textarea-bordered h-32"></textarea>
                </div>

                {{-- Foto actual --}}
                @if ($author->photo_url)
                    <div class="flex items-center gap-3 mt-3">
                        <img src="{{ $author->photo_url }}" class="w-20 h-20 rounded-full object-cover border">
                        <span class="text-sm text-gray-500">Foto actual</span>
                    </div>
                @endif

                {{-- Nueva foto --}}
                <div class="form-control mt-3">
                    <label class="label"><span class="label-text font-semibold">Reemplazar foto</span></label>
                    <input type="file" wire:model="photo" class="file-input file-input-bordered w-full" />
                    @if ($photo)
                        <div class="mt-4">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-20 h-20 rounded-full border">
                            <span class="text-sm text-gray-500 ml-2">Vista previa</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.authors.index') }}" wire:navigate class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
        </div>
    </form>
</div>
