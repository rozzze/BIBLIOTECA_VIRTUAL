<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportUsers extends Component
{
    use WithFileUploads;

    public $file;

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,txt|max:5120', // hasta 5MB
        ]);

        Excel::import(new UsersImport, $this->file->getRealPath());

        session()->flash('success', 'Usuarios importados/actualizados correctamente.');
        $this->reset('file');
    }

    public function render()
    {
        return view('livewire.admin.import-users');
    }
}
