<?php

namespace App\Livewire\Admin\Books;

use Livewire\Component;
use App\Models\Book;
use Livewire\Attributes\Layout;


class Show extends Component
{
    // El libro que estamos mostrando
    public $book;

    /**
     * El método mount recibe el libro directamente desde la ruta.
     * Carga las relaciones para evitar consultas N+1.
     */
    public function mount(Book $book)
    {
        $this->book = $book->load('author', 'publisher', 'category');
    }

    /**
     * Renderiza la vista.
     */
    public function render()
    {
        return view('livewire.admin.books.show');
    }
}