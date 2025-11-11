<div class="container mx-auto p-6 max-w-5xl">
    {{-- 🧭 Encabezado --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.books.index') }}" wire:navigate class="btn btn-circle btn-ghost">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6l4 2m6 3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Editar Libro
                </h1>
                <p class="text-indigo-100 mt-2">Modifica los datos del libro: 
                    <span class="font-semibold">{{ $book->title }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- 🧾 Formulario --}}
    <form wire:submit.prevent="update" class="card bg-base-100 shadow-xl p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Título --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Título</span></label>
                <input type="text" wire:model="title" class="input input-bordered input-primary" />
                @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Autor --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Autor</span></label>
                <select wire:model="author_id" class="select select-bordered select-primary">
                    <option value="">Seleccionar autor...</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
                @error('author_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Editorial --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Editorial</span></label>
                <select wire:model="publisher_id" class="select select-bordered select-primary">
                    <option value="">Seleccionar editorial...</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </select>
                @error('publisher_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Categoría --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Categoría</span></label>
                <select wire:model="category_id" class="select select-bordered select-primary">
                    <option value="">Seleccionar categoría...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Año --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Año de publicación</span></label>
                <input type="number" wire:model="publication_year" class="input input-bordered input-primary" />
            </div>

            {{-- Idioma --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Idioma</span></label>
                <input type="text" wire:model="language" class="input input-bordered input-primary" />
            </div>

            {{-- Páginas --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">N° de páginas</span></label>
                <input type="number" wire:model="pages" class="input input-bordered input-primary" />
            </div>

            {{-- Stock --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Stock</span></label>
                <input type="number" wire:model="stock" class="input input-bordered input-primary" />
            </div>

            {{-- Portada --}}
            <div class="form-control col-span-full">
                <label class="label"><span class="label-text font-semibold">Portada</span></label>
                <input type="file" wire:model="cover" accept="image/*"
                    class="file-input file-input-bordered file-input-primary w-full" />
                @error('cover') <span class="text-error text-sm">{{ $message }}</span> @enderror

                <div class="flex gap-6 mt-4">
                    @if ($cover)
                        <div>
                            <span class="text-sm text-zinc-500">Nueva portada:</span>
                            <img src="{{ $cover->temporaryUrl() }}" class="w-40 h-56 object-cover rounded-lg mt-2 border" />
                        </div>
                    @endif
                    @if ($book->cover_url)
                        <div>
                            <span class="text-sm text-zinc-500">Actual:</span>
                            <img src="{{ $book->cover_url }}" class="w-40 h-56 object-cover rounded-lg mt-2 border" />
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resumen --}}
            <div class="form-control col-span-full">
                <label class="label"><span class="label-text font-semibold">Resumen / Descripción</span></label>
                <textarea wire:model="summary" class="textarea textarea-bordered textarea-primary h-32"></textarea>
            </div>
        </div>

        {{-- Botones --}}
        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.books.index') }}" wire:navigate class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>
