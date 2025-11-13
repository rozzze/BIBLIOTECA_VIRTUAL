<?php

// 1. El namespace SIN 'Http'
namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Cleanup extends Component
{
    // Límite en Megabytes antes de mostrar la alerta
    public $limitInMb = 100;

    public $sizeInMb = 0;
    public $isOverLimit = false;

    // Almacenará el disco que usa Livewire (ej: 'public' o 's3')
    private $disk;

    /**
     * 2. El 'mount' corregido, se ejecuta al cargar
     */
    public function mount()
    {
        // Determina el disco a usar (local o producción)
        $this->disk = config('livewire.temporary_file_upload.disk') ?? config('filesystems.default');
        
        $this->checkStorage();
    }

    /**
     * Lógica para calcular el tamaño de la carpeta temporal.
     */
    public function checkStorage()
    {
        try {
            $path = 'livewire-tmp';
            // 3. Usando el disco 'public' o 's3' (el correcto)
            $files = Storage::disk($this->disk)->files($path);
            $sizeInBytes = 0;

            foreach ($files as $file) {
                if (basename($file) !== '.gitignore') {
                    // 4. Usando el disco correcto
                    $sizeInBytes += Storage::disk($this->disk)->size($file);
                }
            }

            $this->sizeInMb = round($sizeInBytes / 1024 / 1024, 2);
            $this->isOverLimit = $this->sizeInMb > $this->limitInMb;

        } catch (\Exception $e) {
            Log::error("Error al calcular tamaño de livewire-tmp en disco '{$this->disk}': " . $e->getMessage());
            $this->sizeInMb = -1; // Usamos -1 para indicar un error
        }
    }

    /**
     * Vacía COMPLETAMENTE la carpeta livewire-tmp.
     */
    public function clearTmpFolder()
    {
        try {
            $path = 'livewire-tmp';
            // 5. Usando el disco correcto
            $files = Storage::disk($this->disk)->files($path);
            $count = 0;

            foreach ($files as $file) {
                if (basename($file) !== '.gitignore') {
                    // 6. Usando el disco correcto
                    Storage::disk($this->disk)->delete($file);
                    $count++;
                }
            }
            
            // Actualiza el tamaño (debería ser 0)
            $this->checkStorage();
            session()->flash('success', "✅ ¡Limpieza completada! Se eliminaron $count archivos del disco '{$this->disk}'.");

        } catch (\Exception $e) {
            Log::error("Error al limpiar livewire-tmp en disco '{$this->disk}': " . $e->getMessage());
            session()->flash('error', "Error al limpiar la carpeta: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.maintenance.cleanup');
    }
}