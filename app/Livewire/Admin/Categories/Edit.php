<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Category $category;
    public $name;
    public $description;
    public $color;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->color = $category->color;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($this->category->id)],
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ];
    }

    public function update()
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($validated['name']);

        $this->category->update($validated);

        session()->flash('success', '✅ Categoría actualizada correctamente.');
        return redirect()->route('admin.categories.index');
    }

    public function render()
    {
        return view('livewire.admin.categories.edit');
    }
}
