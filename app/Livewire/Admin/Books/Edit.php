<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
// 👇 1. IMPORTAR LIBRERÍAS
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Edit extends Component
{
    use WithFileUploads;

    public Book $book;

    public $title;
    public $author_id;
    public $publisher_id;
    public $category_id;
    public $publication_year;
    public $language;
    public $pages;
    public $stock;
    public $summary;
    public $cover; // Este será null o un TemporaryUploadedFile

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->title = $book->title;
        $this->author_id = $book->author_id;
        $this->publisher_id = $book->publisher_id;
        $this->category_id = $book->category_id;
        $this->publication_year = $book->publication_year;
        $this->language = $book->language;
        $this->pages = $book->pages;
        $this->stock = $book->stock;
        $this->summary = $book->summary;
    }

    protected function rules()
    {
        return [
            // Ignora el título actual al buscar duplicados
            'title' => 'required|string|max:200|unique:books,title,' . $this->book->id,
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'publication_year' => 'nullable|integer|digits:4|min:1500|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1|max:20000',
            'stock' => 'required|integer|min:0',
            'summary' => 'nullable|string|max:3000',
            // 👇 2. PERMITIR IMÁGENES MÁS GRANDES PARA OPTIMIZAR
            'cover' => 'nullable|image|max:8192', // 8MB
        ];
    }
    
    protected $messages = [
        'author_id.required' => 'Debes seleccionar un autor.',
        'publisher_id.required' => 'Debes seleccionar una editorial.',
        'category_id.required' => 'Debes seleccionar una categoría.',
    ];

    public function update()
    {
        $validated = $this->validate();

        $disk = config('filesystems.default');
        // Mantiene la ruta de la imagen antigua por defecto
        $path = $this->book->cover_path; 

        // 👇 3. LÓGICA DE OPTIMIZACIÓN AL ACTUALIZAR
        if ($this->cover) {
            try {
                // Borra la imagen antigua si existe
                if ($path && Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }

                // Genera un nombre de archivo único
                $filename = $this->cover->hashName();
                $targetPath = 'books/' . $filename;

                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->cover->getRealPath());
                $image->scaleDown(width: 800);
                $encodedImage = $image->toJpeg(80);

                // Guarda la nueva imagen optimizada
                Storage::disk($disk)->put($targetPath, (string) $encodedImage);
                $path = $targetPath; // Actualiza $path a la nueva ruta

            } catch (\Exception $e) {
                Log::error('Error al optimizar imagen (Edit Book): ' . $e->getMessage());
                $this->addError('cover', 'Error al procesar la imagen.');
                return;
            }
        }
        // 👆 FIN DE LA LÓGICA DE OPTIMIZACIÓN

        $this->book->update([
            'title' => $this->title,
            'author_id' => $this->author_id,
            'publisher_id' => $this->publisher_id,
            'category_id' => $this->category_id,
            'publication_year' => $this->publication_year,
            'language' => $this->language,
            'pages' => $this->pages,
            'stock' => $this->stock,
            'summary' => $this->summary,
            'cover_path' => $path, // Guarda la ruta (nueva o la antigua)
        ]);

        session()->flash('success', '✅ Libro actualizado correctamente.');
        // Redirige al 'show' o 'index'
        return redirect()->route('admin.books.show', $this->book->id); 
    }

    public function render()
    {
        return view('livewire.admin.books.edit', [
            'authors' => Author::orderBy('name')->get(['id', 'name']),
            'publishers' => Publisher::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }
}