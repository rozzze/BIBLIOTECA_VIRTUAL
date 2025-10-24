<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class BaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Administrador','Bibliotecario','Alumno'] as $rol) {
            Role::firstOrCreate(['name' => $rol, 'guard_name' => 'web']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@biblioteca.test'],
            ['name' => 'Administrador del Sistema', 'password' => Hash::make('password')]
        );

        if (! $admin->hasRole('Administrador')) {
            $admin->assignRole('Administrador');
        }

        $this->command->info('✅ Roles y admin creados (admin@biblioteca.test / password)');
    }
}
