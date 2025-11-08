<?php

namespace App\Livewire\Admin\Authors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete($id)
    {
        $author = Author::findOrFail($id);

        if ($author->photo_path && Storage::disk(config('filesystems.default'))->exists($author->photo_path)) {
            Storage::disk(config('filesystems.default'))->delete($author->photo_path);
        }

        $author->delete();
        session()->flash('success', '✅ Autor eliminado correctamente.');
    }

    public function render()
    {
        $authors = Author::query()
            ->when($this->search, fn($q) => 
                $q->where('name', 'like', "%{$this->search}%")
            )
            ->latest()
            ->paginate(8);

        return view('livewire.admin.authors.index', compact('authors'));
    }
}
