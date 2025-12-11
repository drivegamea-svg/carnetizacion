<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEspecialidadRequest;
use App\Http\Requests\UpdateEspecialidadRequest;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EspecialidadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Especialidad::query();

            return DataTables::of($query)
                ->editColumn('created_at', fn($especialidad) => $especialidad->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($especialidad) => $especialidad->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('especialidades.index');
    }

    public function create()
    {
        return view('especialidades.create');
    }

    public function store(StoreEspecialidadRequest $request)
    {
        Especialidad::create($request->validated());

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad creada exitosamente.');
    }

    public function edit(Especialidad $especialidad)
    {
        return view('especialidades.edit', compact('especialidad'));
    }

    public function update(UpdateEspecialidadRequest $request, Especialidad $especialidad)
    {
        $especialidad->update($request->validated());

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad actualizada exitosamente.');
    }

    public function destroy(Especialidad $especialidad)
    {
        if ($especialidad->afiliados()->exists()) {
            return redirect()->route('especialidades.index')
                ->with('error', 'No se puede eliminar la especialidad porque tiene afiliados asociados.');
        }

        $especialidad->delete();

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad eliminada exitosamente.');
    }
}