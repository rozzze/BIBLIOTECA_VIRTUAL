<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportBooks extends Component
{
    use WithFileUploads;

    public $file;
    public $validationErrors = [];
    public $importError = null;
    public $successMessage = null;

    // 👇👇 ¡EL MÉTODO SE LLAMA 'save'! 👇👇
    public function save()
    {
        // Limpiamos errores anteriores
        $this->validationErrors = [];
        $this->importError = null;
        $this->successMessage = null;

        // Validamos que sea un archivo Excel
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv' // Acepta Excel y CSV
        ]);

        try {
            // Importar el archivo
            Excel::import(new BooksImport, $this->file);
            
            $this->successMessage = '¡Importación completada! Los libros se han registrado correctamente.';
            $this->reset('file');

        } catch (ValidationException $e) {
            // Capturar errores de validación de Maatwebsite (los 'rules' del importador)
            $this->validationErrors = $e->failures();
        
        } catch (\Exception $e) {
            // Capturar cualquier otro error (ej. de la base de datos)
            $this->importError = 'Error inesperado: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.import-books');
    }
}