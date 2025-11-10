<div class="container mx-auto p-6 max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">🏢 Editoriales</h1>
        <a href="{{ route('admin.publishers.create') }}" wire:navigate class="btn btn-primary">➕ Nueva</a>
    </div>

    <input type="text" wire:model.live="search" placeholder="Buscar editorial..." class="input input-bordered w-full mb-5" />

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($publishers as $publisher)
            <div class="card bg-base-100 shadow-xl">
                <figure class="px-4 pt-4">
                    <img src="{{ $publisher->logo_url }}" class="rounded-xl h-32 object-contain" alt="Logo de {{ $publisher->name }}">
                </figure>
                <div class="card-body items-center text-center">
                    <h2 class="card-title text-lg font-bold">{{ $publisher->name }}</h2>
                    <p class="text-sm text-zinc-500">{{ $publisher->country ?? 'Sin país' }}</p>
                    @if ($publisher->website)
                        <a href="{{ $publisher->website }}" target="_blank" class="link link-primary text-sm">Visitar sitio</a>
                    @endif
                    <div class="card-actions mt-3">
                        <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-sm btn-outline btn-info" wire:navigate>Editar</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-zinc-500">No hay editoriales registradas.</p>
        @endforelse
    </div>
</div>
