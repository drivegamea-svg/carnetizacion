<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\Ciudad;
use App\Models\SectorEconomico;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales por estado
        $porEstado = Afiliado::groupBy('estado')
            ->selectRaw('estado as name, COUNT(*) as total')
            ->get();

        // Por sector económico (desde relación)
        $porSector = Afiliado::with('sectorEconomico')
            ->select('sector_economico_id', DB::raw('COUNT(*) as total'))
            ->groupBy('sector_economico_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->sectorEconomico->nombre ?? 'No Especificado',
                    'total' => $item->total
                ];
            });

        // Por género
        $porGenero = Afiliado::groupBy('genero')
            ->selectRaw('genero as name, COUNT(*) as total')
            ->get();

        // Por ciudad (top 10) - desde relación
        $porCiudad = Afiliado::with('ciudad')
            ->select('ciudad_id', DB::raw('COUNT(*) as total'))
            ->groupBy('ciudad_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->ciudad->nombre ?? 'No Especificado',
                    'total' => $item->total
                ];
            });

        // Afiliaciones por mes
        $porMes = DB::table('afiliados')
            ->where('fecha_afiliacion', '>=', Carbon::now()->subYear())
            ->whereNotNull('fecha_afiliacion')
            ->select(DB::raw("
                TO_CHAR(fecha_afiliacion, 'YYYY-MM') as periodo,
                COUNT(*) as total
            "))
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::createFromFormat('Y-m', $item->periodo)
                            ->locale('es')
                            ->translatedFormat('M Y'),
                    'total' => $item->total
                ];
            });

        // Totales para las tarjetas
        $totalAfiliados = Afiliado::count();
        $afiliadosActivos = Afiliado::activos()->count();
        $afiliadosPendientes = Afiliado::pendientes()->count();
        $afiliadosInactivos = Afiliado::inactivos()->count();

        return view('dashboard/index', compact(
            'porEstado',
            'porSector',
            'porGenero',
            'porCiudad',
            'porMes',
            'totalAfiliados',
            'afiliadosActivos',
            'afiliadosPendientes',
            'afiliadosInactivos'
        ));
    }

    // Método alternativo si necesitas más control
    public function estadisticasAvanzadas()
    {
        // Por rango de edad
        $porEdad = DB::table('afiliados')
            ->selectRaw("
                CASE 
                    WHEN EXTRACT(YEAR FROM AGE(fecha_nacimiento)) < 25 THEN '18-24'
                    WHEN EXTRACT(YEAR FROM AGE(fecha_nacimiento)) BETWEEN 25 AND 34 THEN '25-34'
                    WHEN EXTRACT(YEAR FROM AGE(fecha_nacimiento)) BETWEEN 35 AND 44 THEN '35-44'
                    WHEN EXTRACT(YEAR FROM AGE(fecha_nacimiento)) BETWEEN 45 AND 54 THEN '45-54'
                    ELSE '55+'
                END as rango_edad,
                COUNT(*) as total
            ")
            ->whereNotNull('fecha_nacimiento')
            ->groupBy('rango_edad')
            ->orderBy('rango_edad')
            ->get();

        // Por profesión técnica (desde relación)
        $porProfesion = Afiliado::with('profesionTecnica')
            ->select('profesion_tecnica_id', DB::raw('COUNT(*) as total'))
            ->groupBy('profesion_tecnica_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->profesionTecnica->nombre ?? 'No Especificado',
                    'total' => $item->total
                ];
            });

        return response()->json([
            'por_edad' => $porEdad,
            'por_profesion' => $porProfesion
        ]);
    }

    // Método para obtener estadísticas en tiempo real (API)
    public function apiEstadisticas()
    {
        $totalAfiliados = Afiliado::count();
        $afiliadosActivos = Afiliado::activos()->count();
        $afiliadosPendientes = Afiliado::pendientes()->count();
        $afiliadosInactivos = Afiliado::inactivos()->count();

        // Afiliados del mes actual
        $afiliadosEsteMes = Afiliado::whereYear('fecha_afiliacion', Carbon::now()->year)
            ->whereMonth('fecha_afiliacion', Carbon::now()->month)
            ->count();

        // Afiliados de la semana actual
        $afiliadosEstaSemana = Afiliado::whereBetween('fecha_afiliacion', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        return response()->json([
            'totales' => [
                'total' => $totalAfiliados,
                'activos' => $afiliadosActivos,
                'pendientes' => $afiliadosPendientes,
                'inactivos' => $afiliadosInactivos,
                'este_mes' => $afiliadosEsteMes,
                'esta_semana' => $afiliadosEstaSemana
            ],
            'porcentajes' => [
                'activos' => $totalAfiliados > 0 ? round(($afiliadosActivos / $totalAfiliados) * 100, 1) : 0,
                'pendientes' => $totalAfiliados > 0 ? round(($afiliadosPendientes / $totalAfiliados) * 100, 1) : 0,
                'inactivos' => $totalAfiliados > 0 ? round(($afiliadosInactivos / $totalAfiliados) * 100, 1) : 0
            ]
        ]);
    }

    // Método para obtener datos para gráficos
    public function datosGraficos()
    {
        // Por sector económico
        $porSector = Afiliado::with('sectorEconomico')
            ->select('sector_economico_id', DB::raw('COUNT(*) as total'))
            ->groupBy('sector_economico_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->sectorEconomico->nombre ?? 'No Especificado',
                    'total' => $item->total
                ];
            });

        // Por ciudad
        $porCiudad = Afiliado::with('ciudad')
            ->select('ciudad_id', DB::raw('COUNT(*) as total'))
            ->groupBy('ciudad_id')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->ciudad->nombre ?? 'No Especificado',
                    'total' => $item->total
                ];
            });

    

        return response()->json([
            'sectores' => $porSector,
            'ciudades' => $porCiudad,
        ]);
    }
}