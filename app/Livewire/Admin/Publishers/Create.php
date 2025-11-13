<?php

// Nota: Asumo que el namespace es este basado en tu proyecto
namespace App\Livewire\Admin\Publishers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Publisher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// 👇 1. IMPORTAR LA LIBRERÍA DE IMÁGENES
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Create extends Component
{
    use WithFileUploads;

    // Propiedades del formulario (basado en tu modelo Publisher)
    public $name;
    public $country;
    public $website;
    public $description;
    public $logo; // Asumo que el campo se llama 'logo' en el form

    protected $rules = [
        'name' => 'required|string|max:255|unique:publishers,name',
        'country' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:8192', // 8MB
    ];

    public function save()
    {
        $validated = $this->validate();

        $path = null;

        // 👇 2. LÓGICA DE OPTIMIZACIÓN
        if ($this->logo) {
            try {
                $disk = config('filesystems.default');
                $filename = $this->logo->hashName();
                // Ojo con el nombre de la carpeta
                $targetPath = 'publishers/' . $filename; 

                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->logo->getRealPath());
                
                // Redimensiona a 800px de ancho
                $image->scaleDown(width: 800);

                $encodedImage = $image->toJpeg(80);

                Storage::disk($disk)->put($targetPath, (string) $encodedImage);
                $path = $targetPath;

            } catch (\Exception $e) {
                Log::error('Error al optimizar logo de editorial: ' . $e->getMessage());
                $this->addError('logo', 'Error al procesar la imagen.');
                return;
            }
        }
        // 👆 FIN DE LA LÓGICA DE OPTIMIZACIÓN

        Publisher::create([
            'name' => $this->name,
            'country' => $this->country,
            'website' => $this->website,
            'description' => $this->description,
            'logo_path' => $path, // Asigna la ruta optimizada
        ]);

        session()->flash('success', '✅ Editorial creada correctamente.');
        return redirect()->route('admin.publishers.index');
    }

    public function render()
    {
        // Asumo que tu vista se llama así
        return view('livewire.admin.publishers.create');
    }
}