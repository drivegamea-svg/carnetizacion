<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstadisticasExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $estadisticas;

    public function __construct($estadisticas)
    {
        $this->estadisticas = $estadisticas;
    }

    public function array(): array
    {
        return $this->estadisticas;
    }

    public function headings(): array
    {
        return ['CATEGORÍA', 'VALOR'];
    }

    public function title(): string
    {
        return 'Estadísticas Generales';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Encabezado
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2C3E50']]
            ],
            
            // Todas las celdas
            'A:B' => [
                'alignment' => ['wrapText' => true],
            ],
        ];
    }
}