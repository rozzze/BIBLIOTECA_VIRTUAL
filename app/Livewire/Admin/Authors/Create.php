<?php

// Nota: Asumo que el namespace es este basado en tu proyecto
namespace App\Livewire\Admin\Authors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// 👇 1. IMPORTAR LA LIBRERÍA DE IMÁGENES
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Create extends Component
{
    use WithFileUploads;

    // Propiedades del formulario (basado en tu modelo Author)
    public $name;
    public $nationality;
    public $biography;
    public $photo;

    protected $rules = [
        'name' => 'required|string|max:120',
        'nationality' => 'nullable|string|max:80',
        'biography' => 'nullable|string',
        'photo' => 'nullable|image|max:8192', // 8MB
    ];

    public function save()
    {
        $validated = $this->validate();

        $path = null;

        // 👇 2. LÓGICA DE OPTIMIZACIÓN
        if ($this->photo) {
            try {
                $disk = config('filesystems.default');
                $filename = $this->photo->hashName();
                $targetPath = 'authors/' . $filename;

                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->photo->getRealPath());
                
                // Redimensiona a 800px de ancho
                $image->scaleDown(width: 800);

                $encodedImage = $image->toJpeg(80);

                Storage::disk($disk)->put($targetPath, (string) $encodedImage);
                $path = $targetPath;

            } catch (\Exception $e) {
                Log::error('Error al optimizar foto de autor: ' . $e->getMessage());
                $this->addError('photo', 'Error al procesar la imagen.');
                return;
            }
        }
        // 👆 FIN DE LA LÓGICA DE OPTIMIZACIÓN

        Author::create([
            'name' => $this->name,
            'nationality' => $this->nationality,
            'biography' => $this->biography,
            'photo_path' => $path, // Asigna la ruta optimizada
        ]);

        session()->flash('success', '✅ Autor creado correctamente.');
        return redirect()->route('admin.authors.index');
    }

    public function render()
    {
        // Asumo que tu vista se llama así
        return view('livewire.admin.authors.create');
    }
}