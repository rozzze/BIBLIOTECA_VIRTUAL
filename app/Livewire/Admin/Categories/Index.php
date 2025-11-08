<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash('success', '✅ Categoría eliminada correctamente.');
    }

    public function render()
    {
        $categories = Category::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(8);

        return view('livewire.admin.categories.index', compact('categories'));
    }
}
