<?php

namespace App\Livewire\Admin\Authors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public $name, $nationality, $biography, $photo;

    protected $rules = [
        'name' => 'required|string|max:120',
        'nationality' => 'nullable|string|max:80',
        'biography' => 'nullable|string',
        'photo' => 'nullable|image|max:2048', // hasta 2 MB
    ];

    public function save()
    {
        $validated = $this->validate();

        $disk = config('filesystems.default');
        $path = $this->photo ? $this->photo->store('authors', $disk) : null;

        Author::create([
            'name' => $this->name,
            'nationality' => $this->nationality,
            'biography' => $this->biography,
            'photo_path' => $path,
        ]);

        session()->flash('success', '✅ Autor creado correctamente.');
        return redirect()->route('admin.authors.index');
    }

    public function render()
    {
        return view('livewire.admin.authors.create');
    }
}
