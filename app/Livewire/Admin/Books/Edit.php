<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
    public $cover;

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
            'title' => 'required|string|max:255|unique:books,title,' . $this->book->id,
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

    public function update()
    {
        $validated = $this->validate();

        $disk = config('filesystems.default');
        $path = $this->book->cover_path;

        // 🖼️ Si sube nueva imagen → eliminar la anterior
        if ($this->cover) {
            if ($path && Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
            $path = $this->cover->store('books', $disk);
        }

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
            'cover_path' => $path,
        ]);

        session()->flash('success', '✅ Libro actualizado correctamente.');
        return redirect()->route('admin.books.index');
    }

    public function render()
    {
        return view('livewire.admin.books.edit', [
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
