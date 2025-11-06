<?php

namespace App\Livewire\Admin\Careers;

use Livewire\Component;
use App\Models\Career;

class Create extends Component
{
    public $name, $abbreviation, $description;

    protected $rules = [
        'name' => 'required|string|max:120|unique:careers,name',
        'abbreviation' => 'nullable|string|max:15',
        'description' => 'nullable|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        Career::create([
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Carrera creada correctamente.');
        return redirect()->route('admin.careers.index');
    }

    public function render()
    {
        return view('livewire.admin.careers.create')
            ->layout('components.layouts.app');
    }
}
