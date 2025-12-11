<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Models\Role; //validaciones store user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();

            return DataTables::of($query)
                ->addColumn('actions', function ($user) {
                    return view('admin.usuarios.partials.actions', compact('user'))->render();
                })
                ->rawColumns(['actions']) // <-- Esta línea es la clave
                ->editColumn('created_at', fn($user) => $user->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($user) => $user->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('admin.usuarios.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(StoreUsuarioRequest $request)
    {
        // La validación se ha hecho automáticamente
        $validated = $request->validated();

        // Unir nombres y apellidos
        $nombre_completo = $validated['nombres'] . ' ' . $validated['apellidos'];

        // Crear el nuevo usuario
        $usuario = User::create([
            'name'     => $nombre_completo,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'ci'       => $validated['ci'],
            'genero'   => $validated['genero'],
            'cargo'    => $validated['cargo'],
            'celular'  => $validated['celular'],
        ]);

        // Asignar el rol
        $usuario->roles()->attach($validated['rol']);

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

}
