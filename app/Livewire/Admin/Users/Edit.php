<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Career;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public string $role = '';
    public string $name = '';
    public string $email = '';
    public ?string $password = null;

    // Campos de alumno
    public ?string $dni = null;
    public ?int $carrera_id = null;
    public ?string $turno = null;
    public ?string $semestre = null;
    public ?string $telefono = null;
    public ?string $direccion = null;

    public array $roles = [];
    public $careers;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->roles = Role::pluck('name')->toArray();
        $this->careers = Career::orderBy('name')->get();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';

        if ($user->studentProfile) {
            $this->dni = $user->studentProfile->dni;
            $this->carrera_id = $user->studentProfile->carrera_id;
            $this->turno = $user->studentProfile->turno;
            $this->semestre = $user->studentProfile->semestre;
            $this->telefono = $user->studentProfile->telefono;
            $this->direccion = $user->studentProfile->direccion;
        }
    }

    protected function rules()
    {
        $rules = [
            'role' => ['required', Rule::in($this->roles)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => 'nullable|string|min:6',
        ];

        if ($this->role === 'Alumno') {
            $rules = array_merge($rules, [
                'dni' => ['required', 'string', 'max:20', Rule::unique('student_profiles', 'dni')->ignore(optional($this->user->studentProfile)->id)],
                'carrera_id' => 'required|exists:careers,id',
                'turno' => 'required|in:Diurno,Nocturno',
                'semestre' => 'required|in:1,2,3,4,5,6',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ]);
        }

        return $rules;
    }

    public function update()
    {
        $validated = $this->validate();

        // Actualizar usuario
        $this->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $this->password ? Hash::make($this->password) : $this->user->password,
        ]);

        $this->user->syncRoles([$validated['role']]);

        // Perfil de alumno
        if ($validated['role'] === 'Alumno') {
            StudentProfile::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'dni' => $this->dni,
                    'carrera_id' => $this->carrera_id,
                    'turno' => $this->turno,
                    'semestre' => $this->semestre,
                    'telefono' => $this->telefono,
                    'direccion' => $this->direccion,
                ]
            );
        } else {
            $this->user->studentProfile()?->delete();
        }

        session()->flash('success', '✅ Usuario actualizado correctamente.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.edit', [
            'roles' => $this->roles,
            'careers' => $this->careers,
        ]);
    }
}
