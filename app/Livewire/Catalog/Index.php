<?php

namespace App\Livewire\Catalog;

use Livewire\Component;
use Livewire\Attributes\Layout; // <--- Importa esto

// Apunta al "Shell" del estudiante
#[Layout('components.layouts.student')] 
class Index extends Component
{
    public function render()
    {
        return view('livewire.catalog.index');
    }
}