<?php

namespace App\Exports;

use App\Models\Afiliado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AfiliadosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $afiliados;

    public function __construct($afiliados)
    {
        $this->afiliados = $afiliados;
    }

    public function collection()
    {
        return $this->afiliados;
    }

    public function headings(): array
    {
        return [
            'CI',
            'EXPEDICIÓN',
            'NOMBRES',
            'APELLIDO PATERNO', 
            'APELLIDO MATERNO',
            'FECHA NACIMIENTO',
            'GÉNERO',
            'CELULAR',
            'CIUDAD',
            'PROFESIÓN/TÉCNICA',
            'ESPECIALIDAD',
            'EMPRESA',
            'SECTOR ECONÓMICO',
            'ESTADO',
            'FECHA AFILIACIÓN',
            'FECHA VENCIMIENTO'
        ];
    }

    public function map($afiliado): array
    {
        return [
            $afiliado->ci,
            $afiliado->expedicion,
            $afiliado->nombres,
            $afiliado->apellido_paterno ?? '',
            $afiliado->apellido_materno ?? '',
            $afiliado->fecha_nacimiento ? $afiliado->fecha_nacimiento->format('d/m/Y') : '',
            $afiliado->genero,
            $afiliado->celular,
            $afiliado->ciudad,
            $afiliado->profesion_tecnica,
            $afiliado->especialidad,
            $afiliado->empresa ?? '',
            $afiliado->sector_economico,
            $afiliado->estado,
            $afiliado->fecha_afiliacion ? $afiliado->fecha_afiliacion->format('d/m/Y') : '',
            $afiliado->fecha_vencimiento ? $afiliado->fecha_vencimiento->format('d/m/Y') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2C3E50']]
            ],
            
            // Estilo para todas las celdas
            'A:Z' => [
                'alignment' => ['wrapText' => true],
            ],
            
            // Centrar columnas específicas
            'A:B' => ['alignment' => ['horizontal' => 'center']],
            'G:H' => ['alignment' => ['horizontal' => 'center']],
            'N:N' => ['alignment' => ['horizontal' => 'center']],
            'O:P' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}