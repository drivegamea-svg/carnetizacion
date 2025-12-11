<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectorEconomicoRequest;
use App\Http\Requests\UpdateSectorEconomicoRequest;
use App\Models\SectorEconomico;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectorEconomicoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SectorEconomico::query();

            return DataTables::of($query)
                ->editColumn('created_at', fn($sectorEconomico) => $sectorEconomico->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($sectorEconomico) => $sectorEconomico->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('sectores-economicos.index');
    }

    public function create()
    {
        return view('sectores-economicos.create');
    }

    public function store(StoreSectorEconomicoRequest $request)
    {
        SectorEconomico::create($request->validated());

        return redirect()->route('sectores-economicos.index')
            ->with('success', 'Sector económico creado exitosamente.');
    }

    public function edit(SectorEconomico $sectorEconomico)
    {
        return view('sectores-economicos.edit', compact('sectorEconomico'));
    }

    public function update(UpdateSectorEconomicoRequest $request, SectorEconomico $sectorEconomico)
    {
        $sectorEconomico->update($request->validated());

        return redirect()->route('sectores-economicos.index')
            ->with('success', 'Sector económico actualizado exitosamente.');
    }

    public function destroy(SectorEconomico $sectorEconomico)
    {
        if ($sectorEconomico->afiliados()->exists()) {
            return redirect()->route('sectores-economicos.index')
                ->with('error', 'No se puede eliminar el sector económico porque tiene afiliados asociados.');
        }

        $sectorEconomico->delete();

        return redirect()->route('sectores-economicos.index')
            ->with('success', 'Sector económico eliminado exitosamente.');
    }
}