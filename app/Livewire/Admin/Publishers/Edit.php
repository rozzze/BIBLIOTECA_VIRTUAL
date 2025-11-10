<?php

namespace App\Livewire\Admin\Publishers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Publisher;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $publisher;
    public $name, $country, $website, $description, $logo;

    public function mount(Publisher $publisher)
    {
        $this->publisher = $publisher;
        $this->name = $publisher->name;
        $this->country = $publisher->country;
        $this->website = $publisher->website;
        $this->description = $publisher->description;
    }

    protected $rules = [
        'name' => 'required|string|max:150',
        'country' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'description' => 'nullable|string|max:2000',
        'logo' => 'nullable|image|max:2048',
    ];

    public function update()
    {
        $this->validate();

        $path = $this->publisher->logo_path;

        if ($this->logo) {
            if ($path && Storage::disk(config('filesystems.default'))->exists($path)) {
                Storage::disk(config('filesystems.default'))->delete($path);
            }
            $path = $this->logo->store('publishers', config('filesystems.default'));
        }

        $this->publisher->update([
            'name' => $this->name,
            'country' => $this->country,
            'website' => $this->website,
            'description' => $this->description,
            'logo_path' => $path,
        ]);

        session()->flash('success', 'Editorial actualizada correctamente ✅');
        return redirect()->route('admin.publishers.index');
    }

    public function render()
    {
        return view('livewire.admin.publishers.edit');
    }
}
