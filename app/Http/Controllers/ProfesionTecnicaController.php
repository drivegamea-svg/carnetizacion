<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfesionTecnicaRequest;
use App\Http\Requests\UpdateProfesionTecnicaRequest;
use App\Models\ProfesionTecnica;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProfesionTecnicaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProfesionTecnica::query();

            return DataTables::of($query)
                ->editColumn('created_at', fn($profesionTecnica) => $profesionTecnica->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($profesionTecnica) => $profesionTecnica->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('profesiones-tecnicas.index');
    }

    public function create()
    {
        return view('profesiones-tecnicas.create');
    }

    public function store(StoreProfesionTecnicaRequest $request)
    {
        ProfesionTecnica::create($request->validated());

        return redirect()->route('profesiones-tecnicas.index')
            ->with('success', 'Profesión técnica creada exitosamente.');
    }

    public function edit(ProfesionTecnica $profesionTecnica)
    {
        return view('profesiones-tecnicas.edit', compact('profesionTecnica'));
    }

    public function update(UpdateProfesionTecnicaRequest $request, ProfesionTecnica $profesionTecnica)
    {
        $profesionTecnica->update($request->validated());

        return redirect()->route('profesiones-tecnicas.index')
            ->with('success', 'Profesión técnica actualizada exitosamente.');
    }

    public function destroy(ProfesionTecnica $profesionTecnica)
    {
        if ($profesionTecnica->afiliados()->exists()) {
            return redirect()->route('profesiones-tecnicas.index')
                ->with('error', 'No se puede eliminar la profesión técnica porque tiene afiliados asociados.');
        }

        $profesionTecnica->delete();

        return redirect()->route('profesiones-tecnicas.index')
            ->with('success', 'Profesión técnica eliminada exitosamente.');
    }
}