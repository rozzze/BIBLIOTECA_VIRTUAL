<?php

namespace App\Livewire\Admin\Careers;

use Livewire\Component;
use App\Models\Career;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public Career $career;
    
    // ✅ Declara propiedades individuales para el binding
    public string $name = '';
    public ?string $abbreviation = null;
    public ?string $description = null;

    public function mount(Career $career)
    {
        $this->career = $career;
        
        // ✅ Carga los valores en las propiedades
        $this->name = $career->name;
        $this->abbreviation = $career->abbreviation;
        $this->description = $career->description;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:120|unique:careers,name,' . $this->career->id,
            'abbreviation' => 'nullable|string|max:15',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        // ✅ Actualiza el modelo con los valores validados
        $this->career->update($validated);

        session()->flash('success', '✅ Carrera actualizada correctamente.');
        
        return redirect()->route('admin.careers.index');
    }

    public function render()
    {
        return view('livewire.admin.careers.edit');
    }
}