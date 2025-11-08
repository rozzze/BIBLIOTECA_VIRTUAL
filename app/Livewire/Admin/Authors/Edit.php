<?php

namespace App\Livewire\Admin\Authors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Author $author;
    public $name, $nationality, $biography, $photo;

    protected $rules = [
        'name' => 'required|string|max:120',
        'nationality' => 'nullable|string|max:80',
        'biography' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
    ];

    public function mount(Author $author)
    {
        $this->author = $author;
        $this->name = $author->name;
        $this->nationality = $author->nationality;
        $this->biography = $author->biography;
    }

    public function update()
    {
        $validated = $this->validate();
        $disk = config('filesystems.default');

        if ($this->photo) {
            if ($this->author->photo_path && Storage::disk($disk)->exists($this->author->photo_path)) {
                Storage::disk($disk)->delete($this->author->photo_path);
            }
            $validated['photo_path'] = $this->photo->store('authors', $disk);
        }

        $this->author->update($validated);

        session()->flash('success', '✅ Autor actualizado correctamente.');
        return redirect()->route('admin.authors.index');
    }

    public function render()
    {
        return view('livewire.admin.authors.edit');
    }
}
