<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;

class Index extends Component
{
    // 1. USA PAGINACIÓN
    use WithPagination;

    // 2. PROPIEDADES DE FILTRADO (deducidas de tu vista)
    public $search = '';
    public $filterCategory = '';
    public $filterAuthor = '';
    public $filterPublisher = '';

    // 3. MÉTODO PARA LIMPIAR (deducido de tu vista)
    public function clearFilters()
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterAuthor = '';
        $this->filterPublisher = '';
        $this->resetPage(); // Reinicia la paginación
    }
    
    // 4. MÉTODO PARA BORRAR (deducido de tu vista)
    public function deleteBook(Book $book)
    {
        // Aquí puedes añadir SoftDeletes (Mejora 2) en el futuro.
        // Por ahora, lo borramos.
        // Nota: Idealmente, maneja la imagen también.
        $book->delete();
        session()->flash('success', 'Libro eliminado correctamente.');
    }
    
    // 5. El MÉTODO RENDER (¡Aquí ocurre la magia!)
    public function render()
    {
        // Inicia la consulta por los libros
        $query = Book::query()
        
            // 👇 ¡¡¡AQUÍ ESTÁ LA MEJORA 1!!! 👇
            // Le decimos a Laravel que cargue las relaciones
            // EN UNA SOLA CONSULTA, antes de empezar.
            ->with(['author', 'publisher', 'category'])
            
            // FILTROS (basados en tu vista)
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('publisher', fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterAuthor, fn($q) => $q->where('author_id', $this->filterAuthor))
            ->when($this->filterPublisher, fn($q) => $q->where('publisher_id', $this->filterPublisher));

        // Paginación
        $books = $query->latest()->paginate(10); // 10 por página (puedes cambiarlo)

        // Carga los datos para tus menús <select>
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        // Pasa todo a la vista
        return view('livewire.admin.books.index', [
            'books' => $books,
            'authors' => $authors,
            'categories' => $categories,
            'publishers' => $publishers,
        ]);
    }
    
    // Escucha cuando los filtros cambian para reiniciar la paginación
    public function updating($key)
    {
        if(in_array($key, ['search', 'filterCategory', 'filterAuthor', 'filterPublisher'])) {
            $this->resetPage();
        }
    }
}