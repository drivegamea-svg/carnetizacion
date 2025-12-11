<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,  // Primero crear los roles y permisos
            AdminSeeder::class,           // Luego crear el usuario admin y asignar el rol
            AdminUserSeeder::class, //solo para pruebas
            OrganizacionSocialSeeder::class,    // ✅ NUEVO: Organizaciones Sociales
            SectorEconomicoSeeder::class, // ✅ NUEVO
            ProfesionTecnicaSeeder::class, // ✅ NUEVO
            EspecialidadSeeder::class, // ✅ NUEVO
            CiudadSeeder::class, // ✅ NUEVO
            AfiliadoFakerSeeder::class,

        ]);
    }
}
