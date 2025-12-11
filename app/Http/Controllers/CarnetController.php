<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Afiliado;
use TCPDF;
use TCPDF_FONTS;

class CarnetController extends Controller
{
    public function carnetPdf($id)
    {
        $afiliado = Afiliado::with(['profesionTecnica', 'especialidad', 'ciudad'])->findOrFail($id);

        // Configuración para carnet PVC horizontal
        $pdf = new TCPDF('L', 'mm', [53.98, 85.6], true, 'UTF-8', false);

        // Configuración básica - márgenes muy pequeños
        $pdf->SetTitle('Carnet de Afiliado - ' . $afiliado->nombres);
        $pdf->SetMargins(2, 2, 2);
        $pdf->SetAutoPageBreak(false);
        $pdf->setFontSubsetting(true);
        $pdf->AddPage();

        // Cargar imagen de fondo completa para el amverso
        $amversoPath = public_path('color-admin/img/carnet/amverso.png');
        $pdf->Image($amversoPath, 0, 0, 85.6, 53.98, '', '', '', false, 300, '', false, false, 0, false, false, false);

        // Área utilizable completa
        $width = 85.6; // Ancho en orientación landscape
        $height = 53.98; // Alto en orientación landscape

        // Foto del afiliado
        $fotoPath = $this->getFotoPath($afiliado->foto_path);
        $fotoX = 4;
        $fotoY = 18;
        $fotoWidth = 22;

        if ($fotoPath && is_file($fotoPath)) {
            $pdf->Image($fotoPath, $fotoX, $fotoY, $fotoWidth, '', '', '', '', false, 300, '', false, false, 1, false, false, false);
        } else {
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->SetXY($fotoX, $fotoY);
            $pdf->Cell($fotoWidth, 3, 'SIN FOTO', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
        }

        // Información personal - POSICIÓN CORREGIDA
        $infoWidth = 55; // Ancho máximo disponible
        
        // Nombre
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 16);
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($afiliado->nombres .' '.$afiliado->apellido_paterno . ' ' . $afiliado->apellido_materno, 25), 0, 1);

        // CI
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 24);
        $pdf->Cell($infoWidth - 8, 4, $afiliado->ci . ' ' . $afiliado->expedicion, 0, 1);

        // Celular
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(60, 24);
        $pdf->Cell($infoWidth - 8, 4, $afiliado->celular, 0, 1);

        // Profesión (desde relación)
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 31);
        $profesion = $afiliado->profesionTecnica->nombre ?? 'N/A';
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($profesion, 22), 0, 1);

        // Especialidad (desde relación)
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 39);
        $especialidad = $afiliado->especialidad->nombre ?? 'N/A';
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($especialidad, 22), 0, 1);

        // Información de fechas - MEJOR POSICIONADO
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetXY(5, 65);
        $pdf->Cell(35, 3, 'Afiliación: ' . ($afiliado->fecha_afiliacion ? $afiliado->fecha_afiliacion->format('d/m/Y') : 'N/A'), 0, 0, 'L');
        
        $pdf->SetXY(45, 65);
        $pdf->Cell(35, 3, 'Vence: ' . ($afiliado->fecha_vencimiento ? $afiliado->fecha_vencimiento->format('d/m/Y') : 'N/A'), 0, 1, 'L');

        // Firma - MEJOR POSICIONADA
        $pdf->SetFont('helvetica', '', 5);
        $pdf->SetXY(30, 68);
        $pdf->Cell(25, 2, 'Firma del titular', 0, 1, 'C');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(30, 71, 55, 71);

        // =========================
        // REVERSO DEL CARNET
        // =========================
        $pdf->AddPage();

        // Cargar imagen de fondo completa para el reverso
        $reversoPath = public_path('color-admin/img/carnet/reverso.png');
        $pdf->Image($reversoPath, 0, 0, 85.6, 53.98, '', '', '', false, 300, '', false, false, 0, false, false, false);

        // QR CODE - POSICIÓN CORREGIDA
        $qrSize = 25;
        $qrX = 54; // Centrado mejor
        $qrY = 14;

        // Generar URL de verificación pública
        $verificationUrl = route('afiliados.show.public', $afiliado->id);
        
        // Generar QR code
        $style = array(
            'border' => 0,
            'vpadding' => 1,
            'hpadding' => 1,
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255),
            'module_width' => 1,
            'module_height' => 1
        );
        
        $pdf->write2DBarcode($verificationUrl, 'QRCODE,L', $qrX, $qrY, $qrSize, $qrSize, $style, 'N');
        
        // Texto bajo el QR
        $pdf->SetTextColor(41, 128, 185);
        $pdf->SetFont('helvetica', 'B', 4);
        $pdf->SetXY($qrX, $qrY + $qrSize);
        $pdf->Cell($qrSize, 3, 'ESCANEAR PARA VERIFICAR', 0, 1, 'C');

        // Salida final
        $fileName = "CARNET_{$afiliado->ci}.pdf";
        $pdfContent = $pdf->Output($fileName, 'S');

        return response()->make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    /**
     * Generar carnets masivos
     */
    public function carnetsMasivos(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No se seleccionaron afiliados.');
        }
        
        $afiliados = Afiliado::with(['profesionTecnica', 'especialidad', 'ciudad'])
            ->where('estado', 'ACTIVO')
            ->whereIn('id', $ids)
            ->get();
        
        if ($afiliados->isEmpty()) {
            return redirect()->back()->with('error', 'No se encontraron afiliados activos para generar carnets.');
        }
        
        // Crear PDF combinado
        $pdf = new TCPDF('L', 'mm', [53.98, 85.6], true, 'UTF-8', false);
        
        foreach ($afiliados as $afiliado) {
            $this->agregarCarnetAlPDF($pdf, $afiliado);
        }
        
        $fileName = "CARNETS_MASIVOS_" . date('Y-m-d') . ".pdf";
        $pdfContent = $pdf->Output($fileName, 'S');

        return response()->make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    /**
     * Generar carnets para todos los afiliados activos
     */
    public function carnetsTodosActivos()
    {
        $afiliados = Afiliado::with(['profesionTecnica', 'especialidad', 'ciudad'])
            ->where('estado', 'ACTIVO')
            ->get();
        
        if ($afiliados->isEmpty()) {
            return redirect()->back()->with('error', 'No hay afiliados activos para generar carnets.');
        }
        
        $pdf = new TCPDF('L', 'mm', [53.98, 85.6], true, 'UTF-8', false);
        
        foreach ($afiliados as $afiliado) {
            $this->agregarCarnetAlPDF($pdf, $afiliado);
        }
        
        $fileName = "CARNETS_TODOS_" . date('Y-m-d') . ".pdf";
        $pdfContent = $pdf->Output($fileName, 'S');

        return response()->make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    /**
     * Método auxiliar para agregar carnet al PDF
     */
    private function agregarCarnetAlPDF($pdf, $afiliado)
    {
        // Configuración para carnet PVC horizontal
        $pdf->SetTitle('Carnets de Afiliados - Lote');
        $pdf->SetMargins(2, 2, 2);
        $pdf->SetAutoPageBreak(false);
        $pdf->setFontSubsetting(true);
        $pdf->AddPage();

        // ANVERSO
        $amversoPath = public_path('color-admin/img/carnet/amverso.png');
        $pdf->Image($amversoPath, 0, 0, 85.6, 53.98, '', '', '', false, 300, '', false, false, 0, false, false, false);

        $width = 85.6;
        $height = 53.98;

        // Foto del afiliado
        $fotoPath = $this->getFotoPath($afiliado->foto_path);
        $fotoX = 4;
        $fotoY = 18;
        $fotoWidth = 22;

        if ($fotoPath && is_file($fotoPath)) {
            $pdf->Image($fotoPath, $fotoX, $fotoY, $fotoWidth, '', '', '', '', false, 300, '', false, false, 1, false, false, false);
        }

        // Información personal
        $infoWidth = 55;
        
        // Nombre
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 16);
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($afiliado->nombres .' '.$afiliado->apellido_paterno . ' ' . $afiliado->apellido_materno, 25), 0, 1);

        // CI
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 24);
        $pdf->Cell($infoWidth - 8, 4, $afiliado->ci . ' ' . $afiliado->expedicion, 0, 1);

        // Celular
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(60, 24);
        $pdf->Cell($infoWidth - 8, 4, $afiliado->celular, 0, 1);

        // Profesión (desde relación)
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 31);
        $profesion = $afiliado->profesionTecnica->nombre ?? 'N/A';
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($profesion, 22), 0, 1);

        // Especialidad (desde relación)
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(32, 39);
        $especialidad = $afiliado->especialidad->nombre ?? 'N/A';
        $pdf->Cell($infoWidth - 12, 4, $this->truncateText($especialidad, 22), 0, 1);

        // Información de fechas
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetXY(5, 65);
        $pdf->Cell(35, 3, 'Afiliación: ' . ($afiliado->fecha_afiliacion ? $afiliado->fecha_afiliacion->format('d/m/Y') : 'N/A'), 0, 0, 'L');
        
        $pdf->SetXY(45, 65);
        $pdf->Cell(35, 3, 'Vence: ' . ($afiliado->fecha_vencimiento ? $afiliado->fecha_vencimiento->format('d/m/Y') : 'N/A'), 0, 1, 'L');

        // Firma
        $pdf->SetFont('helvetica', '', 5);
        $pdf->SetXY(30, 68);
        $pdf->Cell(25, 2, 'Firma del titular', 0, 1, 'C');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(30, 71, 55, 71);

        // REVERSO
        $pdf->AddPage();
        $reversoPath = public_path('color-admin/img/carnet/reverso.png');
        $pdf->Image($reversoPath, 0, 0, 85.6, 53.98, '', '', '', false, 300, '', false, false, 0, false, false, false);

        // QR CODE
        $qrSize = 25;
        $qrX = 54;
        $qrY = 14;

        $verificationUrl = route('afiliados.show.public', $afiliado->id);
        
        $style = array(
            'border' => 0,
            'vpadding' => 1,
            'hpadding' => 1,
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255),
            'module_width' => 1,
            'module_height' => 1
        );
        
        $pdf->write2DBarcode($verificationUrl, 'QRCODE,L', $qrX, $qrY, $qrSize, $qrSize, $style, 'N');
        
        // Texto bajo el QR
        $pdf->SetTextColor(41, 128, 185);
        $pdf->SetFont('helvetica', 'B', 4);
        $pdf->SetXY($qrX, $qrY + $qrSize);
        $pdf->Cell($qrSize, 3, 'ESCANEAR PARA VERIFICAR', 0, 1, 'C');
    }

    /**
     * Obtener la ruta completa de la foto
     */
    private function getFotoPath($fotoPath)
    {
        if (empty($fotoPath)) {
            return null;
        }

        // Si ya es una ruta completa, usarla directamente
        if (str_starts_with($fotoPath, '/') || str_starts_with($fotoPath, public_path())) {
            return $fotoPath;
        }

        // Si es una ruta relativa desde storage, construir la ruta pública
        $publicPath = public_path('storage/' . ltrim($fotoPath, '/'));
        
        // También verificar sin el prefijo 'storage/' 
        $alternativePath = public_path(ltrim($fotoPath, '/'));
        
        if (is_file($publicPath)) {
            return $publicPath;
        } elseif (is_file($alternativePath)) {
            return $alternativePath;
        }

        // Último intento: buscar en storage_path
        $storagePath = storage_path('app/public/' . ltrim($fotoPath, '/'));

        return is_file($storagePath) ? $storagePath : null;
    }

    private function truncateText($text, $length)
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length - 3) . '...';
        }
        return $text;
    }
}