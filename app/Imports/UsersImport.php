<?php

namespace App\Imports;

use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Espera encabezados: nombre, email, dni, carrera, semestre,
     * codigo_institucional, telefono, direccion
     */
    public function model(array $row)
    {
        // 1) Crear / actualizar usuario por email
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['nombre'],
                // Si el user es nuevo no tiene password, ponemos una temporal
                'password' => isset($row['password']) && $row['password']
                                ? Hash::make($row['password'])
                                : (User::where('email', $row['email'])->exists()
                                    ? User::where('email', $row['email'])->value('password')
                                    : Hash::make('cambiar123')),
            ]
        );

        // 2) Asignar rol Alumno si no lo tiene
        if (! $user->hasRole('Alumno')) {
            $user->assignRole('Alumno');
        }

        // 3) Crear / actualizar perfil del estudiante
        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'dni'                  => $row['dni'] ?? '',
                'carrera'              => $row['carrera'] ?? null,
                'semestre'             => $row['semestre'] ?? null,
                'codigo_institucional' => $row['codigo_institucional'] ?? null,
                'telefono'             => $row['telefono'] ?? null,
                'direccion'            => $row['direccion'] ?? null,
            ]
        );

        return $user;
    }
}
