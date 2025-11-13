<?php

namespace App\Livewire\Admin\Publishers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Publisher;
use Illuminate\Support\Facades\Storage;
// 👇 1. IMPORTAR LIBRERÍAS
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Edit extends Component
{
    use WithFileUploads;

    public Publisher $publisher; // Usar Route-Model binding es más limpio
    public $name, $country, $website, $description, $logo;
    
    public $existingLogo; // Para vista previa

    public function mount(Publisher $publisher)
    {
        $this->publisher = $publisher;
        $this->name = $publisher->name;
        $this->country = $publisher->country;
        $this->website = $publisher->website;
        $this->description = $publisher->description;
        $this->existingLogo = $publisher->logo_url;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:150|unique:publishers,name,' . $this->publisher->id,
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            // 👇 2. PERMITIR IMÁGENES MÁS GRANDES
            'logo' => 'nullable|image|max:8192', // 8MB
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $disk = config('filesystems.default');
        $path = $this->publisher->logo_path; // Ruta antigua

        // 👇 3. LÓGICA DE OPTIMIZACIÓN AL ACTUALIZAR
        if ($this->logo) {
            try {
                // Borra el logo antiguo si existe
                if ($path && Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }

                $filename = $this->logo->hashName();
                $targetPath = 'publishers/' . $filename;

                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->logo->getRealPath());
                $image->scaleDown(width: 800);
                $encodedImage = $image->toJpeg(80);

                Storage::disk($disk)->put($targetPath, (string) $encodedImage);
                $path = $targetPath; // Actualiza $path a la nueva ruta

            } catch (\Exception $e) {
                Log::error('Error al optimizar imagen (Edit Publisher): ' . $e->getMessage());
                $this->addError('logo', 'Error al procesar la imagen.');
                return;
            }
        }
        // 👆 FIN DE LA LÓGICA DE OPTIMIZACIÓN

        $this->publisher->update([
            'name' => $this->name,
            'country' => $this->country,
            'website' => $this->website,
            'description' => $this->description,
            'logo_path' => $path, // Guarda la ruta (nueva o la antigua)
        ]);
        
        // Refresca la vista previa
        $this->existingLogo = $this->publisher->refresh()->logo_url;
        $this->logo = null; // Limpia el input de archivo

        session()->flash('success', 'Editorial actualizada correctamente ✅');
        return redirect()->route('admin.publishers.index');
    }

    public function render()
    {
        return view('livewire.admin.publishers.edit');
    }
}