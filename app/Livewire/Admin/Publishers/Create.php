<?php

namespace App\Livewire\Admin\Publishers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Publisher;

class Create extends Component
{
    use WithFileUploads;

    public $name, $country, $website, $description, $logo;

    protected $rules = [
        'name' => 'required|string|max:150|unique:publishers,name',
        'country' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'description' => 'nullable|string|max:2000',
        'logo' => 'nullable|image|max:2048', // hasta 2MB
    ];

    public function save()
    {
        $this->validate();

        $path = $this->logo ? $this->logo->store('publishers', config('filesystems.default')) : null;

        Publisher::create([
            'name' => $this->name,
            'country' => $this->country,
            'website' => $this->website,
            'description' => $this->description,
            'logo_path' => $path,
        ]);

        session()->flash('success', 'Editorial creada correctamente ✅');
        return redirect()->route('admin.publishers.index');
    }

    public function render()
    {
        return view('livewire.admin.publishers.create');
    }
}
