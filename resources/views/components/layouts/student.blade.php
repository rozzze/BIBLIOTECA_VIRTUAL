{{-- 
  Esto le dice a Laravel: 
  "Toma el slot de Livewire, envuélvelo en <flux:main> 
   y pásalo todo al slot de 'x-layouts.student.sidebar'"
--}}
<x-layouts.student.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.student.sidebar>