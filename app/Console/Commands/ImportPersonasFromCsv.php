<?php

namespace App\Console\Commands;

use App\Models\Persona;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ImportPersonasFromCsv extends Command
{
    protected $signature = 'import:personas 
                            {file : Ruta del archivo CSV}
                            {--delimiter=; : Delimitador del CSV}
                            {--headers : Si el archivo tiene encabezados}';
    
    protected $description = 'Importar personas desde archivo CSV';

    public function handle()
    {
        $filePath = $this->argument('file');
        $delimiter = $this->option('delimiter');
        $hasHeaders = $this->option('headers');

        // Verificar que el archivo existe
        if (!file_exists($filePath)) {
            $this->error("El archivo {$filePath} no existe.");
            return 1;
        }

        $this->info("Iniciando importación desde: {$filePath}");
        $this->info("Delimitador: {$delimiter}");
        $this->info("Tiene encabezados: " . ($hasHeaders ? 'Sí' : 'No'));

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("No se pudo abrir el archivo.");
            return 1;
        }

        // Si tiene encabezados, leer la primera línea
        if ($hasHeaders) {
            fgetcsv($handle, 0, $delimiter);
        }

        $total = 0;
        $successful = 0;
        $errors = 0;
        $batch = [];

        $this->output->progressStart();

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $total++;
            
            try {
                $personaData = $this->processRow($row);
                
                if ($personaData) {
                    $batch[] = $personaData;
                    
                    // Insertar en lotes de 100
                    if (count($batch) >= 100) {
                        $this->insertBatch($batch, $successful, $errors);
                        $batch = [];
                    }
                } else {
                    $errors++;
                }
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error procesando fila {$total}: " . $e->getMessage());
            }

            $this->output->progressAdvance();
        }

        // Insertar el último lote
        if (!empty($batch)) {
            $this->insertBatch($batch, $successful, $errors);
        }

        fclose($handle);
        $this->output->progressFinish();

        $this->info("\nImportación completada:");
        $this->info("Total procesadas: {$total}");
        $this->info("Éxitos: {$successful}");
        $this->info("Errores: {$errors}");

        return 0;
    }

    private function processRow(array $row)
    {
        // Estructura esperada según tu ejemplo:
        // CI; Expedicion; Nombres; Apellido Paterno; Apellido Materno; Fecha Nacimiento; ?; Genero; Zona; Direccion; Nro Puerta; Ciudad
        
        if (count($row) < 11) {
            $this->warn("Fila con formato incorrecto: " . implode(';', $row));
            return null;
        }

        // Limpiar y mapear los datos
        $ci = trim($row[0]);
        $expedicion = trim($row[1]);
        $nombres = trim($row[2]);
        $apellido_paterno = trim($row[3]);
        $apellido_materno = trim($row[4]);
        $fecha_nacimiento = trim($row[5]);
        $genero = trim($row[7]);
        $zona = trim($row[8]);
        $direccion = trim($row[9]);
        $nro_puerta = trim($row[10]);
        $ciudad = trim($row[11]);

        // Validaciones básicas
        if (empty($ci) || empty($nombres) || empty($fecha_nacimiento)) {
            $this->warn("Fila con datos faltantes: CI={$ci}");
            return null;
        }

        // Procesar fecha de nacimiento
        try {
            $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $fecha_nacimiento)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->warn("Fecha inválida: {$row[5]} para CI: {$ci}");
            return null;
        }

        // Determinar departamento basado en la ciudad
        $departamento = $this->determinarDepartamento($ciudad);

        // Limpiar campos (remover comillas extras)
        $zona = trim($zona, '"\'');
        $direccion = trim($direccion, '"\'');
        $nro_puerta = trim($nro_puerta, '"\'');
        $ciudad = trim($ciudad, '"\'');

        return [
            'ci' => $ci,
            'expedicion' => $expedicion,
            'nombres' => $nombres,
            'apellido_paterno' => $apellido_paterno ?: null,
            'apellido_materno' => $apellido_materno ?: null,
            'fecha_nacimiento' => $fecha_nacimiento,
            'genero' => $genero,
            'estado_civil' => null, // No viene en el CSV
            'celular' => null, // No viene en el CSV
            'departamento' => $departamento,
            'ciudad' => $ciudad,
            'zona' => $zona ?: null,
            'direccion' => $direccion ?: null,
            'nro_puerta' => $nro_puerta ?: null,
            'domicilio' => $this->construirDomicilioCompleto($zona, $direccion, $nro_puerta, $ciudad, $departamento),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    private function determinarDepartamento($ciudad)
    {
        $ciudad = strtoupper(trim($ciudad));
        
        $departamentos = [
            'LA PAZ' => ['LA PAZ', 'EL ALTO'],
            'ORURO' => ['ORURO'],
            'COCHABAMBA' => ['COCHABAMBA'],
            'SANTA CRUZ' => ['SANTA CRUZ'],
            'POTOSI' => ['POTOSÍ', 'POTOSI'],
            'TARIJA' => ['TARIJA'],
            'CHUQUISACA' => ['SUCRE'],
            'BENI' => ['TRINIDAD'],
            'PANDO' => ['COBIJA']
        ];

        foreach ($departamentos as $depto => $ciudades) {
            if (in_array($ciudad, $ciudades)) {
                return $depto;
            }
        }

        // Si no se encuentra, determinar por ciudad conocida
        if (str_contains($ciudad, 'ALTO')) {
            return 'LA PAZ';
        }

        return 'LA PAZ'; // Por defecto
    }

    private function construirDomicilioCompleto($zona, $direccion, $nro_puerta, $ciudad, $departamento)
    {
        $partes = [];
        if ($zona) $partes[] = $zona;
        if ($direccion) $partes[] = $direccion;
        if ($nro_puerta) $partes[] = 'N° ' . $nro_puerta;
        
        return implode(', ', $partes);
    }

    private function insertBatch($batch, &$successful, &$errors)
    {
        try {
            DB::beginTransaction();

            foreach ($batch as $data) {
                // Usar updateOrCreate para evitar duplicados
                Persona::updateOrCreate(
                    ['ci' => $data['ci']],
                    $data
                );
                $successful++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errors += count($batch);
            Log::error("Error en lote: " . $e->getMessage());
        }
    }
}