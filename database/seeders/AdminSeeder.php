<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos base
        $nombre = 'WILMER';
        $apellido = 'ZAMBRANA TICONA';
        $email = 'zambranawilmer96@gmail.com';
        $ci = '10020292';
        $celular = '65155196';
        $genero = 'M';
        $cargo = 'TECNICO DE DESARROLLO DE SOFTWARE';

        // Crear o actualizar usuario admin
        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $nombre . ' ' . $apellido, // Campo legacy (Laravel Breeze lo usa)
                'ci' => $ci,
                'celular' => $celular,
                'genero' => $genero,
                'cargo' => $cargo,
                'password' => bcrypt('Admin@123'),
            ]
        );

        // Asignar rol
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $admin->syncRoles([$adminRole->id]);
        }
    }
}
