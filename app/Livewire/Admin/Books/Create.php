<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// 👇 1. IMPORTAR LA LIBRERÍA DE IMÁGENES
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // O ImagickDriver si lo prefieres

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $author_id;
    public $publisher_id;
    public $category_id;
    public $publication_year;
    public $language;
    public $pages;
    public $stock = 1;
    public $summary;
    public $cover;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:200|unique:books,title',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'publication_year' => 'nullable|integer|digits:4|min:1500|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1|max:20000',
            'stock' => 'required|integer|min:0',
            'summary' => 'nullable|string|max:3000',
            'cover' => 'nullable|image|max:8192', // Aumentamos a 8MB por si acaso
        ];
    }

    protected $messages = [
        'author_id.required' => 'Debes seleccionar un autor.',
        'publisher_id.required' => 'Debes seleccionar una editorial.',
        'category_id.required' => 'Debes seleccionar una categoría.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $path = null;

        // 👇 2. LÓGICA DE OPTIMIZACIÓN
        if ($this->cover) {
            try {
                // Determina el disco (public, s3, etc.)
                $disk = config('filesystems.default');
                // Genera un nombre de archivo único
                $filename = $this->cover->hashName();
                $targetPath = 'books/' . $filename;

                // Crea una instancia del manager
                $manager = new ImageManager(new Driver());

                // Lee la imagen subida
                $image = $manager->read($this->cover->getRealPath());

                // Redimensiona si es necesario (ej: 800px de ancho)
                $image->scaleDown(width: 800);

                // Codifica la imagen (puedes ajustar calidad)
                $encodedImage = $image->toJpeg(80); // 80% calidad

                // Guarda la imagen optimizada en el disco
                Storage::disk($disk)->put($targetPath, (string) $encodedImage);

                $path = $targetPath; // Guarda la ruta correcta

            } catch (\Exception $e) {
                Log::error('Error al optimizar imagen de libro: ' . $e->getMessage());
                // Opcional: notificar al usuario que falló la subida
                $this->addError('cover', 'Error al procesar la imagen.');
                return;
            }
        }
        // 👆 FIN DE LA LÓGICA DE OPTIMIZACIÓN

        // 🗃️ Crear el libro
        Book::create([
            'title' => $validated['title'],
            'author_id' => $validated['author_id'],
            'publisher_id' => $validated['publisher_id'],
            'category_id' => $validated['category_id'],
            'publication_year' => $validated['publication_year'],
            'language' => $validated['language'],
            'pages' => $validated['pages'],
            'stock' => $validated['stock'],
            'summary' => $validated['summary'],
            'cover_path' => $path, // Asigna la ruta optimizada
        ]);

        session()->flash('success', '✅ Libro registrado correctamente.');
        return redirect()->route('admin.books.index');
    }

    public function render()
    {
        return view('livewire.admin.books.create', [
            'authors' => Author::orderBy('name')->get(['id', 'name']),
            'publishers' => Publisher::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }
}