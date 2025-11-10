<?php

namespace App\Livewire\Admin\Publishers;

use Livewire\Component;
use App\Models\Publisher;

class Index extends Component
{
    public $search = '';

    public function render()
    {
        $publishers = Publisher::where('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->get();

        return view('livewire.admin.publishers.index', [
            'publishers' => $publishers,
        ]);
    }
}
