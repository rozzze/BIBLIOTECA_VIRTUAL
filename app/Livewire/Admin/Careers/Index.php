<?php

namespace App\Livewire\Admin\Careers;

use Livewire\Component;
use App\Models\Career;

class Index extends Component
{
    public $search = '';

    public function render()
    {
        $careers = Career::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->get();

        return view('livewire.admin.careers.index', compact('careers'))
            ->layout('components.layouts.app');
    }

    public function delete($id)
    {
        Career::findOrFail($id)->delete();
        session()->flash('success', 'Carrera eliminada correctamente.');
    }
}
