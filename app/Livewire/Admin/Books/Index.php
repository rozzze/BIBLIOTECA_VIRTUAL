<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterAuthor = '';
    public string $filterPublisher = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterAuthor' => ['except' => ''],
        'filterPublisher' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCategory() { $this->resetPage(); }
    public function updatingFilterAuthor() { $this->resetPage(); }
    public function updatingFilterPublisher() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->reset('search','filterCategory','filterAuthor','filterPublisher');
        $this->resetPage();
    }

    public function deleteBook(int $id)
    {
        $book = Book::findOrFail($id);

        // Si en el futuro hay préstamos, aquí harías la validación para no borrar con préstamos activos.

        // borrar portada si existe
        $disk = config('filesystems.default');
        if ($book->cover_path && Storage::disk($disk)->exists($book->cover_path)) {
            Storage::disk($disk)->delete($book->cover_path);
        }

        $book->delete();

        session()->flash('success', '✅ Libro eliminado correctamente.');
        // Mantiene la página actual pero si quedó vacía, vuelve a la anterior
        if ($this->page > $this->paginator->lastPage()) {
            $this->previousPage();
        }
    }

    public function render()
    {
        $query = Book::query()
            ->with(['author','publisher','category'])
            ->when($this->search, function ($q) {
                $term = "%{$this->search}%";
                $q->where(function ($w) use ($term) {
                    $w->where('title','like',$term)
                      ->orWhere('language','like',$term)
                      ->orWhere('summary','like',$term)
                      ->orWhereHas('author', fn($a)=>$a->where('name','like',$term))
                      ->orWhereHas('publisher', fn($p)=>$p->where('name','like',$term))
                      ->orWhereHas('category', fn($c)=>$c->where('name','like',$term));
                });
            })
            ->when($this->filterCategory, fn($q)=>$q->where('category_id',$this->filterCategory))
            ->when($this->filterAuthor, fn($q)=>$q->where('author_id',$this->filterAuthor))
            ->when($this->filterPublisher, fn($q)=>$q->where('publisher_id',$this->filterPublisher))
            ->latest('id');

        return view('livewire.admin.books.index', [
            'books' => $query->paginate(10),
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
        ]);
    }
}
