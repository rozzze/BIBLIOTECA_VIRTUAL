<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
    <h2 class="text-xl font-semibold mb-2">📥 Importar usuarios del instituto</h2>
    <p class="text-sm text-zinc-500 mb-4">
        Sube un archivo <strong>.xlsx</strong> o <strong>.csv</strong> con los siguientes encabezados:
        <code>nombre,email,rol,dni,carrera,turno,semestre,telefono,direccion,password</code>
    </p>

    <form wire:submit.prevent="import" class="space-y-4">
        <input type="file" wire:model="file" accept=".xlsx,.csv"
               class="w-full text-sm border border-zinc-300 dark:border-zinc-700 rounded-lg p-2 bg-white dark:bg-zinc-900"/>

        @error('file')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Importar
            </button>
        </div>
    </form>

    <div class="mt-4">
        @if (session('success'))
            <p class="text-green-600 font-medium">{{ session('success') }}</p>
        @endif
    </div>
</div>
