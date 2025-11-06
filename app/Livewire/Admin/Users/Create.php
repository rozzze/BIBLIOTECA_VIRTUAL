<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Career;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public $name, $email, $password, $role;
    public $dni, $telefono, $direccion, $carrera_id, $turno, $semestre;

    public $roles = [];
    public $careers = [];

    public function mount()
    {
        $this->roles = Role::pluck('name')->toArray();
        $this->careers = Career::orderBy('name')->get();
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:' . implode(',', $this->roles),
        ];

        if ($this->role === 'Alumno') {
            $rules = array_merge($rules, [
                'dni' => 'required|string|max:20|unique:student_profiles,dni',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'carrera_id' => 'required|exists:careers,id',
                'turno' => 'required|in:Diurno,Nocturno',
                'semestre' => 'required|in:1,2,3,4,5,6',
            ]);
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        if ($this->role === 'Alumno') {
            StudentProfile::create([
                'user_id' => $user->id,
                'dni' => $this->dni,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'carrera_id' => $this->carrera_id,
                'turno' => $this->turno,
                'semestre' => $this->semestre,
            ]);
        }

        session()->flash('success', 'Usuario creado correctamente.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.create')
            ->layout('components.layouts.app');
    }
}
