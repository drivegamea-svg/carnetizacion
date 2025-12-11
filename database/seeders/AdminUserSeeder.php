<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Asegúrate de que exista el rol admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

     
    }
}
