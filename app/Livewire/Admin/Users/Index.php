<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterRole() { $this->resetPage(); }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->hasRole('Administrador')) {
            session()->flash('error', '❌ No puedes eliminar otro administrador.');
            return;
        }

        $user->studentProfile()?->delete();
        $user->delete();

        session()->flash('success', '✅ Usuario eliminado correctamente.');
        $this->dispatch('userDeleted');
    }

    public function render()
    {
        $query = User::with(['roles', 'studentProfile.career'])
            ->when($this->search, fn($q) => 
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->filterRole, fn($q) => 
                $q->whereHas('roles', fn($r) => $r->where('name', $this->filterRole))
            )
            ->orderBy('name');

        $users = $query->paginate(8);
        $roles = Role::pluck('name')->toArray();

        return view('livewire.admin.users.index', compact('users', 'roles'));
    }
}
