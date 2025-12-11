<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir permisos agrupados por grupo
        $permissionGroups = [
            'Ciudades'          => [
                'ver ciudades',
                'crear ciudadades',
                'editar ciudades',
                'eliminar ciudades',
                'ver ciudades eliminadas',
            ],
          
            'Afiliados CIZEE' => [
                'ver afiliados',
                'crear afiliados', 
                'editar afiliados',
                'eliminar afiliados',
                'ver afiliados eliminados',
                'activar afiliados',
                'desactivar afiliados',
                'restaurar afiliados',
                'imprimir carnets afiliados',
            ],

          'Reportes' => [
                'ver reportes afiliados',
                'exportar excel afiliados',
                'exportar pdf afiliados',
                'ver reportes estadisticos',
                'exportar excel estadisticos', 
                'exportar pdf estadisticos',
                'ver reportes sectores',
                'exportar excel sectores',
                'exportar pdf sectores',
            ],


            
            'Organizaciones Sociales' => [
                'ver organizaciones sociales',
                'crear organizaciones sociales', 
                'editar organizaciones sociales',
                'eliminar organizaciones sociales',
            ],

            'Sectores Económicos' => [
                'ver sectores economicos',
                'crear sectores economicos', 
                'editar sectores economicos',
                'eliminar sectores economicos',
            ],

            // En RolePermissionSeeder.php, agregar este grupo:
            'Profesiones Técnicas' => [
                'ver profesiones tecnicas',
                'crear profesiones tecnicas', 
                'editar profesiones tecnicas',
                'eliminar profesiones tecnicas',
            ],

            // En RolePermissionSeeder.php, agregar este grupo:
            'Especialidades' => [
                'ver especialidades',
                'crear especialidades', 
                'editar especialidades',
                'eliminar especialidades',
            ],

            // En RolePermissionSeeder.php, ya tienes este grupo, solo verificar:
            'Ciudades' => [
                'ver ciudades',
                'crear ciudades', 
                'editar ciudades',
                'eliminar ciudades',
                'ver ciudades eliminadas', // Este ya existe
            ],

        ];

        // Crear permisos con su grupo
        foreach ($permissionGroups as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(
                    ['name' => $perm],
                    ['group_name' => $group, 'guard_name' => 'web']
                );
            }
        }

        // Crear rol y asignar permisos
        $admin = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());
    }
}