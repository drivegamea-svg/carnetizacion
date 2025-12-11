<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCiudadRequest;
use App\Http\Requests\UpdateCiudadRequest;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CiudadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ciudad::query();

            return DataTables::of($query)
                ->editColumn('created_at', fn($ciudad) => $ciudad->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($ciudad) => $ciudad->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('ciudades.index');
    }

    public function create()
    {
        return view('ciudades.create');
    }

    

    public function store(StoreCiudadRequest $request)
    {
        Ciudad::create($request->validated());

        return redirect()->route('ciudades.index')
            ->with('success', 'Ciudad creada exitosamente.');
    }

    public function edit(Ciudad $ciudad)
    {
        return view('ciudades.edit', compact('ciudad'));
    }

    public function update(UpdateCiudadRequest $request, Ciudad $ciudad)
    {
        $ciudad->update($request->validated());

        return redirect()->route('ciudades.index')
            ->with('success', 'Ciudad actualizada exitosamente.');
    }

    public function destroy(Ciudad $ciudad)
    {
        if ($ciudad->afiliados()->exists()) {
            return redirect()->route('ciudades.index')
                ->with('error', 'No se puede eliminar la ciudad porque tiene afiliados asociados.');
        }

        $ciudad->delete();

        return redirect()->route('ciudades.index')
            ->with('success', 'Ciudad eliminada exitosamente.');
    }





}