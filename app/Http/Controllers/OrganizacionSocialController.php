<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizacionSocialRequest;
use App\Http\Requests\UpdateOrganizacionSocialRequest;
use App\Models\OrganizacionSocial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrganizacionSocialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = OrganizacionSocial::query();

            return DataTables::of($query)
                ->editColumn('created_at', fn($organizacionSocial) => $organizacionSocial->created_at?->format('Y-m-d H:i'))
                ->editColumn('updated_at', fn($organizacionSocial) => $organizacionSocial->updated_at?->format('Y-m-d H:i'))
                ->make(true);
        }

        return view('organizaciones-sociales.index');
    }

    public function create()
    {
        return view('organizaciones-sociales.create');
    }

    public function store(StoreOrganizacionSocialRequest $request)
    {
        OrganizacionSocial::create($request->validated());

        return redirect()->route('organizaciones-sociales.index')
            ->with('success', 'Organización social creada exitosamente.');
    }

    public function edit(OrganizacionSocial $organizacionSocial)
    {
        return view('organizaciones-sociales.edit', compact('organizacionSocial'));
    }

    public function update(UpdateOrganizacionSocialRequest $request, OrganizacionSocial $organizacionSocial)
    {
        $organizacionSocial->update($request->validated());

        return redirect()->route('organizaciones-sociales.index')
            ->with('success', 'Organización social actualizada exitosamente.');
    }

    public function destroy(OrganizacionSocial $organizacionSocial)
    {
        if ($organizacionSocial->afiliados()->exists()) {
            return redirect()->route('organizaciones-sociales.index')
                ->with('error', 'No se puede eliminar la organización social porque tiene afiliados asociados.');
        }

        $organizacionSocial->delete();

        return redirect()->route('organizaciones-sociales.index')
            ->with('success', 'Organización social eliminada exitosamente.');
    }
}