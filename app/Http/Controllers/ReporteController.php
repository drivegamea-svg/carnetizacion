<?php
// app/Http/Controllers/ReporteController.php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Exports\AfiliadosExport;
use App\Exports\EstadisticasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        // Obtener estadísticas rápidas para el dashboard
        $totalAfiliados = Afiliado::count();
        $afiliadosActivos = Afiliado::where('estado', 'ACTIVO')->count();
        $afiliadosInactivos = Afiliado::where('estado', 'INACTIVO')->count();
        
        return view('reportes.index', compact(
            'totalAfiliados',
            'afiliadosActivos', 
            'afiliadosInactivos'
        ));
    }

    public function reporteAfiliados()
    {
        $sectores = Afiliado::distinct()->pluck('sector_economico');
        $ciudades = Afiliado::distinct()->pluck('ciudad');
        
        return view('reportes.afiliados', compact('sectores', 'ciudades'));
    }

    public function generarReporteAfiliados(Request $request)
    {
        $query = Afiliado::query();

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('sector_economico')) {
            $query->where('sector_economico', $request->sector_economico);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('ciudad')) {
            $query->where('ciudad', 'like', '%' . $request->ciudad . '%');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('solo_activos')) {
            $query->where('estado', 'ACTIVO');
        }

        $afiliados = $query->get();
        $filtros = $request->all();

        // Si es exportación
        if ($request->has('exportar')) {
            $filename = 'afiliados_' . date('Y_m_d_His') . '.' . 
                       ($request->exportar == 'excel' ? 'xlsx' : 'pdf');

            if ($request->exportar == 'excel') {
                return Excel::download(new AfiliadosExport($afiliados), $filename);
            }

            if ($request->exportar == 'pdf') {
                return $this->generarPdfAfiliados($afiliados, $filtros);
            }
        }

        // Vista previa
        return view('reportes.resultados-afiliados', compact('afiliados', 'filtros'));
    }

    private function generarPdfAfiliados($afiliados, $filtros)
    {
        // Crear instancia de TCPDF
        $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('CIZEE');
        $pdf->SetAuthor('CIZEE');
        $pdf->SetTitle('Reporte de Afiliados');
        $pdf->SetSubject('Reporte de Afiliados');

        // Agregar página
        $pdf->AddPage();

        // Contenido del PDF
        $html = $this->vistaPdfAfiliados($afiliados, $filtros);
        
        // Escribir HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida
        $pdf->Output('afiliados_' . date('Y_m_d_His') . '.pdf', 'D');
    }

    private function vistaPdfAfiliados($afiliados, $filtros)
    {
        $html = '
        <style>
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #f2f2f2; font-weight: bold; padding: 8px; border: 1px solid #ddd; }
            td { padding: 6px; border: 1px solid #ddd; }
            .header { text-align: center; margin-bottom: 20px; }
            .filtros { margin-bottom: 15px; padding: 10px; background-color: #f9f9f9; }
            .total { font-weight: bold; margin-top: 10px; }
        </style>

        <div class="header">
            <h1>Reporte de Afiliados - CIZEE</h1>
            <p>Generado el: ' . date('d/m/Y H:i') . '</p>
        </div>';

        // Mostrar filtros aplicados
        if (!empty(array_filter($filtros))) {
            $html .= '<div class="filtros"><strong>Filtros aplicados:</strong><br>';
            if (!empty($filtros['estado'])) $html .= 'Estado: ' . $filtros['estado'] . '<br>';
            if (!empty($filtros['sector_economico'])) $html .= 'Sector: ' . $filtros['sector_economico'] . '<br>';
            if (!empty($filtros['genero'])) $html .= 'Género: ' . $filtros['genero'] . '<br>';
            if (!empty($filtros['ciudad'])) $html .= 'Ciudad: ' . $filtros['ciudad'] . '<br>';
            if (!empty($filtros['fecha_desde'])) $html .= 'Desde: ' . $filtros['fecha_desde'] . '<br>';
            if (!empty($filtros['fecha_hasta'])) $html .= 'Hasta: ' . $filtros['fecha_hasta'] . '<br>';
            if (!empty($filtros['solo_activos'])) $html .= 'Solo activos: Sí<br>';
            $html .= '</div>';
        }

        $html .= '<div class="total">Total de registros: ' . $afiliados->count() . '</div>';

        $html .= '
        <table>
            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombres Completo</th>
                    <th>Celular</th>
                    <th>Ciudad</th>
                    <th>Profesión</th>
                    <th>Especialidad</th>
                    <th>Sector</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($afiliados as $afiliado) {
            $html .= '
                <tr>
                    <td>' . $afiliado->ci . ' ' . $afiliado->expedicion . '</td>
                    <td>' . $afiliado->nombres . ' ' . ($afiliado->apellido_paterno ?? '') . ' ' . ($afiliado->apellido_materno ?? '') . '</td>
                    <td>' . $afiliado->celular . '</td>
                    <td>' . $afiliado->ciudad . '</td>
                    <td>' . $afiliado->profesion_tecnica . '</td>
                    <td>' . $afiliado->especialidad . '</td>
                    <td>' . $afiliado->sector_economico . '</td>
                    <td>' . $afiliado->estado . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        return $html;
    }

    public function reporteEstadisticas()
    {
        $estadisticas = $this->obtenerEstadisticas();
        return view('reportes.estadisticas', compact('estadisticas'));
    }

    public function exportarEstadisticas(Request $request)
    {
        $estadisticas = $this->obtenerEstadisticas();

        if ($request->tipo == 'excel') {
            $datosExcel = [];
            
            // Estadísticas generales
            foreach ($estadisticas['generales'] as $key => $value) {
                $datosExcel[] = [$key, $value];
            }
            
            // Por sector económico
            $datosExcel[] = ['', ''];
            $datosExcel[] = ['SECTOR ECONÓMICO', 'CANTIDAD'];
            foreach ($estadisticas['por_sector'] as $sector) {
                $datosExcel[] = [$sector->name, $sector->total];
            }

            // Por estado
            $datosExcel[] = ['', ''];
            $datosExcel[] = ['ESTADO', 'CANTIDAD'];
            foreach ($estadisticas['por_estado'] as $estado) {
                $datosExcel[] = [$estado->name, $estado->total];
            }

            return Excel::download(new EstadisticasExport($datosExcel), 'estadisticas_' . date('Y_m_d_His') . '.xlsx');
        }

        if ($request->tipo == 'pdf') {
            return $this->generarPdfEstadisticas($estadisticas);
        }
    }

    private function generarPdfEstadisticas($estadisticas)
    {
        $pdf = new \TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('CIZEE');
        $pdf->SetAuthor('CIZEE');
        $pdf->SetTitle('Reporte Estadístico');
        $pdf->SetSubject('Estadísticas de Afiliados');

        $pdf->AddPage();

        $html = $this->vistaPdfEstadisticas($estadisticas);
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output('estadisticas_' . date('Y_m_d_His') . '.pdf', 'D');
    }

    private function vistaPdfEstadisticas($estadisticas)
    {
        $html = '
        <style>
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th { background-color: #2c3e50; color: white; font-weight: bold; padding: 8px; }
            td { padding: 8px; border: 1px solid #ddd; }
            .header { text-align: center; margin-bottom: 20px; }
            .section { margin: 20px 0; }
            .stat-box { background-color: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #3498db; }
        </style>

        <div class="header">
            <h1>Reporte Estadístico - CIZEE</h1>
            <p>Generado el: ' . date('d/m/Y H:i') . '</p>
        </div>';

        // Estadísticas generales
        $html .= '<div class="section">
            <h2>Estadísticas Generales</h2>
            <table>
                <thead>
                    <tr>
                        <th>Indicador</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($estadisticas['generales'] as $key => $value) {
            $html .= '<tr>
                <td>' . $key . '</td>
                <td>' . $value . '</td>
            </tr>';
        }

        $html .= '</tbody></table></div>';

        // Por sector económico
        $html .= '<div class="section">
            <h2>Distribución por Sector Económico</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sector Económico</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>';

        $total = $estadisticas['generales']['Total Afiliados'];
        foreach ($estadisticas['por_sector'] as $sector) {
            $porcentaje = $total > 0 ? round(($sector->total / $total) * 100, 2) : 0;
            $html .= '<tr>
                <td>' . $sector->name . '</td>
                <td>' . $sector->total . '</td>
                <td>' . $porcentaje . '%</td>
            </tr>';
        }

        $html .= '</tbody></table></div>';

        // Por estado
        $html .= '<div class="section">
            <h2>Distribución por Estado</h2>
            <table>
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($estadisticas['por_estado'] as $estado) {
            $porcentaje = $total > 0 ? round(($estado->total / $total) * 100, 2) : 0;
            $html .= '<tr>
                <td>' . $estado->name . '</td>
                <td>' . $estado->total . '</td>
                <td>' . $porcentaje . '%</td>
            </tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    public function reporteSectores()
    {
        $sectores = Afiliado::select('sector_economico', DB::raw('COUNT(*) as total'))
            ->groupBy('sector_economico')
            ->orderByDesc('total')
            ->get();

        $totalGeneral = $sectores->sum('total');

        return view('reportes.sectores', compact('sectores', 'totalGeneral'));
    }

    public function exportarSectores(Request $request)
    {
        $query = Afiliado::select('sector_economico', DB::raw('COUNT(*) as total'))
            ->groupBy('sector_economico');

        // Aplicar ordenamiento
        switch ($request->orden) {
            case 'total_asc':
                $query->orderBy('total');
                break;
            case 'nombre_asc':
                $query->orderBy('sector_economico');
                break;
            case 'nombre_desc':
                $query->orderByDesc('sector_economico');
                break;
            default:
                $query->orderByDesc('total');
        }

        // Aplicar límite
        if ($request->limite > 0) {
            $query->limit($request->limite);
        }

        $sectores = $query->get();

        if ($request->tipo == 'excel') {
            $datosExcel = [];
            
            // Encabezado
            $datosExcel[] = ['SECTOR ECONÓMICO', 'TOTAL AFILIADOS'];
            
            // Datos
            foreach ($sectores as $sector) {
                $datosExcel[] = [$sector->sector_economico, $sector->total];
            }

            // Totales
            $datosExcel[] = ['', ''];
            $datosExcel[] = ['TOTAL GENERAL', $sectores->sum('total')];

            return Excel::download(new EstadisticasExport($datosExcel), 'sectores_economicos_' . date('Y_m_d_His') . '.xlsx');
        }

        if ($request->tipo == 'pdf') {
            return $this->generarPdfSectores($sectores);
        }
    }

    private function generarPdfSectores($sectores)
    {
        $pdf = new \TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('CIZEE');
        $pdf->SetAuthor('CIZEE');
        $pdf->SetTitle('Reporte por Sectores Económicos');
        
        $pdf->AddPage();

        $html = '
        <style>
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #34495e; color: white; padding: 10px; }
            td { padding: 8px; border: 1px solid #ddd; }
            .header { text-align: center; margin-bottom: 20px; }
            .total { font-weight: bold; margin-top: 10px; }
        </style>

        <div class="header">
            <h1>Reporte por Sectores Económicos - CIZEE</h1>
            <p>Generado el: ' . date('d/m/Y H:i') . '</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Sector Económico</th>
                    <th>Total de Afiliados</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>';

        $totalGeneral = $sectores->sum('total');
        foreach ($sectores as $sector) {
            $porcentaje = $totalGeneral > 0 ? round(($sector->total / $totalGeneral) * 100, 2) : 0;
            $html .= '
                <tr>
                    <td>' . $sector->sector_economico . '</td>
                    <td>' . $sector->total . '</td>
                    <td>' . $porcentaje . '%</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        $html .= '<div class="total">Total General: ' . $totalGeneral . ' afiliados</div>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('sectores_economicos_' . date('Y_m_d_His') . '.pdf', 'D');
    }

    private function obtenerEstadisticas()
    {
        return [
            'generales' => [
                'Total Afiliados' => Afiliado::count(),
                'Activos' => Afiliado::where('estado', 'ACTIVO')->count(),
                'Inactivos' => Afiliado::where('estado', 'INACTIVO')->count(),
                'Pendientes' => Afiliado::where('estado', 'PENDIENTE')->count(),
            ],
            'por_sector' => Afiliado::select('sector_economico as name', DB::raw('COUNT(*) as total'))
                ->groupBy('sector_economico')
                ->get(),
            'por_genero' => Afiliado::select('genero as name', DB::raw('COUNT(*) as total'))
                ->groupBy('genero')
                ->get(),
            'por_estado' => Afiliado::select('estado as name', DB::raw('COUNT(*) as total'))
                ->groupBy('estado')
                ->get(),
        ];
    }
}