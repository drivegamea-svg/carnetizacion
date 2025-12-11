<?php
// database/seeders/SectorEconomicoSeeder.php

namespace Database\Seeders;

use App\Models\SectorEconomico;
use Illuminate\Database\Seeder;

class SectorEconomicoSeeder extends Seeder
{
    public function run()
    {
        $sectoresEconomicos = [
            [
                'nombre' => 'INDUSTRIA',
                'descripcion' => 'SECTOR DEDICADO A LA TRANSFORMACIÓN DE MATERIAS PRIMAS EN PRODUCTOS ELABORADOS. INCLUYE INDUSTRIA MANUFACTURERA, TEXTIL, ALIMENTICIA, QUÍMICA, METALMECÁNICA Y OTRAS ACTIVIDADES INDUSTRIALES.'
            ],
            [
                'nombre' => 'COMERCIO',
                'descripcion' => 'SECTOR ENCARGADO DE LA COMPRA Y VENTA DE BIENES Y SERVICIOS. COMPRENDE COMERCIO AL POR MAYOR, AL POR MENOR, IMPORTACIÓN, EXPORTACIÓN Y ACTIVIDADES COMERCIALES EN GENERAL.'
            ],
            [
                'nombre' => 'SERVICIOS',
                'descripcion' => 'SECTOR QUE OFRECE SERVICIOS INTANGIBLES A PERSONAS Y EMPRESAS. INCLUYE SERVICIOS PROFESIONALES, EDUCATIVOS, DE SALUD, TURÍSTICOS, FINANCIEROS, TECNOLÓGICOS Y OTROS SERVICIOS.'
            ],
            [
                'nombre' => 'LOGISTICA',
                'descripcion' => 'SECTOR ESPECIALIZADO EN EL TRANSPORTE, ALMACENAMIENTO Y DISTRIBUCIÓN DE BIENES. COMPRENDE TRANSPORTE TERRESTRE, AÉREO, MARÍTIMO, ALMACENAMIENTO Y CADENAS DE SUMINISTRO.'
            ],
            [
                'nombre' => 'TECNOLOGIA',
                'descripcion' => 'SECTOR ENFOCADO EN EL DESARROLLO E INNOVACIÓN TECNOLÓGICA. INCLUYE DESARROLLO DE SOFTWARE, TELECOMUNICACIONES, INTELIGENCIA ARTIFICIAL, ROBÓTICA Y TECNOLOGÍAS EMERGENTES.'
            ],
            [
                'nombre' => 'AGROINDUSTRIA',
                'descripcion' => 'SECTOR QUE COMBINA ACTIVIDADES AGRÍCOLAS CON PROCESOS INDUSTRIALES. COMPRENDE TRANSFORMACIÓN DE PRODUCTOS AGROPECUARIOS, AGROQUÍMICOS, MAQUINARIA AGRÍCOLA Y PROCESAMIENTO DE ALIMENTOS.'
            ],
            [
                'nombre' => 'CONSTRUCCION',
                'descripcion' => 'SECTOR DEDICADO A LA EDIFICACIÓN DE INFRAESTRUCTURA Y CONSTRUCCIÓN CIVIL. INCLUYE CONSTRUCCIÓN RESIDENCIAL, COMERCIAL, OBRAS PÚBLICAS, INFRAESTRUCTURA VIAL Y URBANIZACIÓN.'
            ],
            [
                'nombre' => 'ENERGIA',
                'descripcion' => 'SECTOR ENCARGADO DE LA GENERACIÓN, DISTRIBUCIÓN Y COMERCIALIZACIÓN DE ENERGÍA. COMPRENDE ENERGÍAS RENOVABLES, HIDROCARBUROS, ELECTRICIDAD Y FUENTES ALTERNATIVAS DE ENERGÍA.'
            ],
            [
                'nombre' => 'MINERIA',
                'descripcion' => 'SECTOR DEDICADO A LA EXTRACCIÓN Y PROCESAMIENTO DE RECURSOS MINERALES. INCLUYE MINERÍA METÁLICA, NO METÁLICA, EXPLOTACIÓN DE YACIMIENTOS Y PROCESAMIENTO DE MINERALES.'
            ],
            [
                'nombre' => 'OTRO',
                'descripcion' => 'SECTOR PARA ACTIVIDADES ECONÓMICAS NO CLASIFICADAS EN LAS CATEGORÍAS ANTERIORES. INCLUYE EMPRENDIMIENTOS INNOVADORES, ACTIVIDADES MIXTAS Y SECTORES EMERGENTES.'
            ]
        ];

        foreach ($sectoresEconomicos as $sector) {
            SectorEconomico::create($sector);
        }

        $this->command->info('✅ 10 SECTORES ECONÓMICOS CREADOS EXITOSAMENTE!');
        $this->command->info('📊 SECTORES INCLUIDOS: INDUSTRIA, COMERCIO, SERVICIOS, LOGÍSTICA, TECNOLOGÍA, AGROINDUSTRIA, CONSTRUCCIÓN, ENERGÍA, MINERÍA Y OTRO');
    }
}