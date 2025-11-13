<?php

namespace App\Livewire\Admin\Authors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;
// 👇 1. IMPORTAR LIBRERÍAS
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Edit extends Component
{
    use WithFileUploads;

    public Author $author;
    public $name, $nationality, $biography, $photo;
    
    // Almacena la ruta de la foto existente
    public $existingPhoto;

    public function mount(Author $author)
    {
        $this->author = $author;
        $this->name = $author->name;
        $this->nationality = $author->nationality;
        $this->biography = $author->biography;
        $this->existingPhoto = $author->photo_url; // Para mostrar la vista previa
    }

    protected function rules()
    {
        return [
            // Añade la regla unique para la edición
            'name' => 'required|string|max:120|unique:authors,name,' . $this->author->id,
            'nationality' => 'nullable|string|max:80',
            'biography' => 'nullable|string',
            // 👇 2. PERMITIR IMÁGENES MÁS GRANDES
            'photo' => 'nullable|image|max:8192', // 8MB
        ];
    }

    // 👇👇 AQUÍ ESTÁ EL ARREGLO COMPLETO 👇👇
    public function update()
    {
        // 1. Valida TODOS los campos primero, incluyendo la foto
        $validatedData = $this->validate();

        $disk = config('filesystems.default');
        $path = $this->author->photo_path; // Ruta antigua por defecto

        // 2. Comprueba si se subió una NUEVA foto ($this->photo es el archivo temporal)
        if ($this->photo) {
            try {
                // Borra la foto antigua si existe
                if ($path && Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }

                // Optimiza y guarda la NUEVA foto
                $filename = $this->photo->hashName();
                $targetPath = 'authors/' . $filename;

                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->photo->getRealPath());
                $image->scaleDown(width: 800);
                $encodedImage = $image->toJpeg(80);

                Storage::disk($disk)->put($targetPath, (string) $encodedImage);
                $path = $targetPath; // Actualiza $path a la nueva ruta

            } catch (\Exception $e) {
                Log::error('Error al optimizar imagen (Edit Author): ' . $e->getMessage());
                $this->addError('photo', 'Error al procesar la imagen.');
                return;
            }
        }
        // Si no se subió $this->photo, $path simplemente se queda con la ruta antigua.

        // 3. Prepara el array final para el update
        // Quita la propiedad 'photo' (el FileUpload) de los datos validados
        unset($validatedData['photo']); 
        
        // Añade la ruta de la imagen (nueva o antigua)
        $validatedData['photo_path'] = $path;

        // 4. Actualiza el autor
        $this->author->update($validatedData);
        
        // Refresca la vista previa
        $this->existingPhoto = $this->author->refresh()->photo_url;
        $this->photo = null; // Limpia el input de archivo

        session()->flash('success', '✅ Autor actualizado correctamente.');
        return redirect()->route('admin.authors.index');
    }
    // 👆👆 FIN DEL ARREGLO 👆👆

    public function render()
    {
        // Asumo que la vista se llama así
        return view('livewire.admin.authors.edit');
    }
}