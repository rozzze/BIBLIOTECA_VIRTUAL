<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name;
    public $description;
    public $color;

    protected $rules = [
        'name' => 'required|string|max:100|unique:categories,name',
        'description' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:20',
    ];

    public function save()
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        session()->flash('success', '✅ Categoría creada correctamente.');
        return redirect()->route('admin.categories.index');
    }

    public function render()
    {
        return view('livewire.admin.categories.create');
    }
}
