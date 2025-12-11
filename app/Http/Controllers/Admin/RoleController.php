<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;
// asegúrate de tener esto instalado

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Incluye roles eliminados (soft deletes)
            $roles = Role::withTrashed()->select([
                'id', 'name', 'guard_name', 'created_at', 'updated_at', 'deleted_at',
            ]);

            return DataTables::of($roles)
                ->addColumn('permissions_count', function ($role) {
                    return $role->permissions()->count();
                })
                ->addColumn('actions', function ($role) {
                    if ($role->trashed()) {
                        // Acciones si está eliminado
                        $restoreUrl     = route('admin.roles.restore', $role->id);
                        $forceDeleteUrl = route('admin.roles.forceDelete', $role->id);
                       return '
                    
                        <form id="restore-form-' . $role->id . '" action="' . $restoreUrl . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . '
                            <button type="button" class="btn btn-sm btn-outline-warning me-1" title="Restaurar" onclick="confirmRestore(' . $role->id . ')">
                                <i class="fas fa-undo"></i>
                            </button>
                        </form>

                       
                        ';
                    } else {
                        // Acciones si está activo
                        $showUrl   = route('admin.roles.show', $role->id);
                        $editUrl   = route('admin.roles.edit', $role->id);
                        $deleteUrl = route('admin.roles.destroy', $role->id);
                        return '
                        <a href="' . $showUrl . '" class="btn btn-outline-info btn-sm me-1" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-outline-warning btn-sm me-1" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <form id="force-delete-form-' . $role->id . '" action="' . $deleteUrl . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(' . $role->id . ')" title="Eliminar permanentemente">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        
                    ';
                    }
                })
                ->addColumn('users_count', function ($role) {
                    return '<span class="badge bg-secondary">' . $role->users()->count() . '</span>';
                })
                ->rawColumns(['actions'])
                ->rawColumns(['actions', 'users_count'])
                ->make(true);
        }

        //  <form action="' . $forceDeleteUrl . '" method="POST" style="display:inline-block">
        //                     ' . csrf_field() . method_field('DELETE') . '
        //                     <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Eliminar permanentemente?\')" title="Eliminar permanentemente">
        //                         <i class="fas fa-trash-alt"></i>
        //                     </button>
        //                 </form>

        return view('admin.roles.index');
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name'); // Agrupa por tipo
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'permissions'   => 'nullable|array',          // Puede no tener permisos
            'permissions.*' => 'exists:permissions,name', // Cada permiso debe existir en la base de datos
        ]);

        // Crear el rol
        $role = Role::create(['name' => $request->name]);

        // Asignar los permisos seleccionados al rol
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function edit(Role $role)
    {
        $permissions     = Permission::all()->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function show(Role $role)
    {
        $permissions     = Permission::all()->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.show', compact('role', 'permissions', 'rolePermissions'));
    }

    // Eliminar (Soft Delete)
    public function destroy(Role $role)
    {
        // Evitar eliminar el rol "Admin"
        if ($role->name === 'Admin') {
            return back()->with('error', 'No puedes eliminar el rol de administrador principal.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un rol asignado a usuarios.');
        }

        $role->delete();
        return back()->with('success', 'Rol eliminado lógicamente.');
    }


    // Restaurar
    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();
        return back()->with('success', 'Rol restaurado.');
    }

    // Eliminación permanente
    // public function forceDelete($id)
    // {
    //     $role = Role::withTrashed()->findOrFail($id);

    //     if ($role->name === 'Admin') {
    //         return back()->with('error', 'No puedes eliminar permanentemente el rol de administrador.');
    //     }

    //     if (auth()->user()->hasRole($role->name)) {
    //         return back()->with('error', 'No puedes eliminar un rol que estás usando.');
    //     }

    //     $role->forceDelete();
    //     return back()->with('success', 'Rol eliminado permanentemente.');
    // }

}
