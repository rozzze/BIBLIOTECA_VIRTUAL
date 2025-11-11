<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
    public $stock;
    public $summary;
    public $cover;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:books,title',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'publication_year' => 'nullable|integer|min:1500|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1|max:20000',
            'stock' => 'required|integer|min:0',
            'summary' => 'nullable|string|max:3000',
            'cover' => 'nullable|image|max:4096',
        ];
    }


    public function save()
    {
        $validated = $this->validate();

        // 🖼️ Subida de imagen (si existe)
        $path = $this->cover ? $this->cover->store('books', config('filesystems.default')) : null;

        // 🗃️ Crear el libro
        Book::create([
            'title' => $this->title,
            'author_id' => $this->author_id,
            'publisher_id' => $this->publisher_id,
            'category_id' => $this->category_id,
            'publication_year' => $this->publication_year,
            'language' => $this->language,
            'pages' => $this->pages,
            'stock' => $this->stock,
            'summary' => $this->summary,
            'cover_path' => $path,
        ]);

        session()->flash('success', '✅ Libro registrado correctamente.');
        return redirect()->route('admin.books.index');
    }

    public function render()
    {
        return view('livewire.admin.books.create', [
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
