<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Career;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ignorar filas vacías
        if (empty($row['email']) || empty($row['nombre'])) {
            return null;
        }

        // Buscar o crear usuario
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['nombre'],
                'password' => isset($row['password'])
                    ? Hash::make($row['password'])
                    : Hash::make('123456'),
            ]
        );

        // Asignar rol si existe
        if (!empty($row['rol'])) {
            $role = Role::where('name', $row['rol'])->first();
            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        // Si el rol es Alumno, registrar perfil académico
        if (isset($row['rol']) && strtolower($row['rol']) === 'alumno') {
            // Buscar la carrera por nombre (ignorando mayúsculas/minúsculas)
            $career = Career::whereRaw('LOWER(name) = ?', [strtolower($row['carrera'] ?? '')])->first();

            // Si no existe, la crea automáticamente
            if (!$career && !empty($row['carrera'])) {
                $career = Career::create([
                    'name' => $row['carrera'],
                    'abbreviation' => substr($row['carrera'], 0, 5),
                    'description' => 'Carrera importada automáticamente.',
                ]);
            }

            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'dni' => $row['dni'] ?? null,
                    'carrera_id' => $career?->id,
                    'turno' => $row['turno'] ?? null,
                    'semestre' => $row['semestre'] ?? null,
                    'telefono' => $row['telefono'] ?? null,
                    'direccion' => $row['direccion'] ?? null,
                ]
            );
        }

        return $user;
    }
}
