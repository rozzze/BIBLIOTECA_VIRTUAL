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
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13.5M8.253 12H15.75M12 21.75c5.385 0 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25 2.25 6.615 2.25 12s4.365 9.75 9.75 9.75z"></path></svg>
                    Registrar Nuevo Libro
                </h1>
                <p class="text-indigo-100 mt-2">Completa los campos para agregar un libro a la biblioteca.</p>
            </div>
        </div>
    </div>

    {{-- 🧾 Formulario --}}
    <form wire:submit.prevent="save" class="card bg-base-100 shadow-xl p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Título --}}
            <div class="form-control col-span-full">
                <label class="label">
                    <span class="label-text font-semibold">Título <span class="text-error">*</span></span>
                </label>
                <input type="text" wire:model="title" placeholder="Ej: Cien años de soledad"
                    class="input input-bordered input-primary @error('title') input-error @enderror" />
                @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Autor --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Autor <span class="text-error">*</span></span></label>
                <select wire:model="author_id" class="select select-bordered select-primary @error('author_id') select-error @enderror">
                    <option value="">Seleccionar autor...</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
                {{-- AÑADIDO: Mensaje de error --}}
                @error('author_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Editorial --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Editorial <span class="text-error">*</span></span></label>
                <select wire:model="publisher_id" class="select select-bordered select-primary @error('publisher_id') select-error @enderror">
                    <option value="">Seleccionar editorial...</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </select>
                {{-- AÑADIDO: Mensaje de error --}}
                @error('publisher_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Categoría --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Categoría <span class="text-error">*</span></span></label>
                <select wire:model="category_id" class="select select-bordered select-primary @error('category_id') select-error @enderror">
                    <option value="">Seleccionar categoría...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                {{-- AÑADIDO: Mensaje de error --}}
                @error('category_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Año --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Año de publicación</span></label>
                <input type="number" wire:model="publication_year" placeholder="Ej: 2020"
                    class="input input-bordered input-primary @error('publication_year') input-error @enderror" />
                @error('publication_year') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Idioma --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Idioma</span></label>
                <input type="text" wire:model="language" placeholder="Ej: Español"
                    class="input input-bordered input-primary" />
            </div>

            {{-- Páginas --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">N° de páginas</span></label>
                <input type="number" wire:model="pages" placeholder="Ej: 350"
                    class="input input-bordered input-primary" />
            </div>

            {{-- Stock --}}
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Stock disponible <span class="text-error">*</span></span></label>
                <input type="number" wire:model="stock" placeholder="Ej: 10"
                    class="input input-bordered input-primary @error('stock') input-error @enderror" />
                @error('stock') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Portada --}}
            <div class="form-control col-span-full">
                <label class="label"><span class="label-text font-semibold">Portada del libro (Opcional)</span></label>
                <input type="file" wire:model="cover" accept="image/*"
                    class="file-input file-input-bordered file-input-primary w-full" />
                @error('cover') <span class="text-error text-sm">{{ $message }}</span> @enderror

                @if ($cover)
                    <div class="mt-4">
                        <span class="text-sm text-zinc-500">Vista previa:</span>
                        <img src="{{ $cover->temporaryUrl() }}" alt="Vista previa" class="w-40 h-56 object-cover rounded-lg mt-2 border" />
                    </div>
                @endif
            </div>

            {{-- Resumen --}}
            <div class="form-control col-span-full">
                <label class="label"><span class="label-text font-semibold">Resumen / Descripción</span></label>
                <textarea wire:model="summary" class="textarea textarea-bordered textarea-primary h-32"
                    placeholder="Breve descripción del libro..."></textarea>
            </div>
        </div>

        {{-- Botones --}}
        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.books.index') }}" wire:navigate class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove>Guardar libro</span>
                <span wire:loading class="loading loading-spinner loading-sm"></span>
            </button>
        </div>
    </form>
</div>