<?php
// database/seeders/ProfesionTecnicaSeeder.php

namespace Database\Seeders;

use App\Models\ProfesionTecnica;
use Illuminate\Database\Seeder;

class ProfesionTecnicaSeeder extends Seeder
{
    public function run()
    {
        $profesionesTecnicas = [
            [
                'nombre' => 'TÉCNICO EN INFORMÁTICA Y SISTEMAS',
                'descripcion' => 'ESPECIALISTA EN MANTENIMIENTO DE EQUIPOS DE CÓMPUTO, REDES, INSTALACIÓN DE SOFTWARE Y SOPORTE TÉCNICO. DOMINA HERRAMIENTAS OFIMÁTICAS Y SISTEMAS OPERATIVOS.'
            ],
            [
                'nombre' => 'TÉCNICO EN ELECTRICIDAD INDUSTRIAL',
                'descripcion' => 'PROFESIONAL CAPACITADO PARA INSTALACIONES ELÉCTRICAS, MANTENIMIENTO DE MOTORES, TABLEROS DE CONTROL Y SISTEMAS DE POTENCIA INDUSTRIAL.'
            ],
            [
                'nombre' => 'TÉCNICO EN MECÁNICA AUTOMOTRIZ',
                'descripcion' => 'ESPECIALISTA EN DIAGNÓSTICO, REPARACIÓN Y MANTENIMIENTO DE VEHÍCULOS AUTOMOTORES. MANEJA SISTEMAS MECÁNICOS, ELÉCTRICOS Y ELECTRÓNICOS AUTOMOTRICES.'
            ],
            [
                'nombre' => 'TÉCNICO EN CONSTRUCCIÓN CIVIL',
                'descripcion' => 'PROFESIONAL EN OBRAS DE CONSTRUCCIÓN, LEVANTAMIENTOS TOPOGRÁFICOS, INTERPRETACIÓN DE PLANOS Y SUPERVISIÓN DE OBRA.'
            ],
            [
                'nombre' => 'TÉCNICO EN ENFERMERÍA',
                'descripcion' => 'ESPECIALISTA EN CUIDADOS DE ENFERMERÍA, ADMINISTRACIÓN DE MEDICAMENTOS, PRIMEROS AUXILIOS Y ASISTENCIA EN PROCEDIMIENTOS MÉDICOS.'
            ],
            [
                'nombre' => 'TÉCNICO EN CONTABILIDAD',
                'descripcion' => 'PROFESIONAL EN REGISTROS CONTABLES, ELABORACIÓN DE ESTADOS FINANCIEROS, FACTURACIÓN, IMPUESTOS Y GESTIÓN ADMINISTRATIVA.'
            ],
            [
                'nombre' => 'TÉCNICO EN GASTRONOMÍA',
                'descripcion' => 'ESPECIALISTA EN TÉCNICAS CULINARIAS, MANIPULACIÓN DE ALIMENTOS, PREPARACIÓN DE PLATOS Y GESTIÓN DE COCINA.'
            ],
            [
                'nombre' => 'TÉCNICO EN MECÁNICA INDUSTRIAL',
                'descripcion' => 'PROFESIONAL EN MANTENIMIENTO DE MAQUINARIA INDUSTRIAL, SOLDADURA, TORNERÍA Y MECANIZADO DE PIEZAS.'
            ],
            [
                'nombre' => 'TÉCNICO EN ELECTRÓNICA',
                'descripcion' => 'ESPECIALISTA EN REPARACIÓN DE EQUIPOS ELECTRÓNICOS, CIRCUITOS, INSTRUMENTACIÓN Y SISTEMAS DE CONTROL.'
            ],
            [
                'nombre' => 'TÉCNICO EN ADMINISTRACIÓN',
                'descripcion' => 'PROFESIONAL EN GESTIÓN ADMINISTRATIVA, ATENCIÓN AL CLIENTE, ARCHIVO, CORRESPONDENCIA Y PROCEDIMIENTOS OFICINA.'
            ],
            [
                'nombre' => 'TÉCNICO EN SEGURIDAD INDUSTRIAL',
                'descripcion' => 'ESPECIALISTA EN PREVENCIÓN DE RIESGOS LABORALES, HIGIENE OCUPACIONAL, SEGURIDAD EN EL TRABAJO Y NORMATIVAS DE SEGURIDAD.'
            ],
            [
                'nombre' => 'TÉCNICO EN HOTELERÍA Y TURISMO',
                'descripcion' => 'PROFESIONAL EN SERVICIOS HOTELEROS, RECEPCIÓN, RESERVAS, ATENCIÓN AL TURISTA Y ORGANIZACIÓN DE EVENTOS.'
            ],
            [
                'nombre' => 'TÉCNICO EN AGRONOMÍA',
                'descripcion' => 'ESPECIALISTA EN PRODUCCIÓN AGRÍCOLA, MANEJO DE CULTIVOS, RIEGO, FERTILIZACIÓN Y TÉCNICAS AGROPECUARIAS.'
            ],
            [
                'nombre' => 'TÉCNICO EN MECÁNICA DIESEL',
                'descripcion' => 'PROFESIONAL EN REPARACIÓN Y MANTENIMIENTO DE MOTORES DIESEL, SISTEMAS DE INYECCIÓN Y EQUIPOS PESADOS.'
            ],
            [
                'nombre' => 'TÉCNICO EN REFRIGERACIÓN Y AIRE ACONDICIONADO',
                'descripcion' => 'ESPECIALISTA EN INSTALACIÓN Y MANTENIMIENTO DE SISTEMAS DE REFRIGERACIÓN, CLIMATIZACIÓN Y AIRE ACONDICIONADO.'
            ],
            [
                'nombre' => 'TÉCNICO EN LABORATORIO CLÍNICO',
                'descripcion' => 'PROFESIONAL EN ANÁLISIS DE MUESTRAS BIOLÓGICAS, PRUEBAS DE LABORATORIO Y TÉCNICAS DE DIAGNÓSTICO CLÍNICO.'
            ],
            [
                'nombre' => 'TÉCNICO EN DISEÑO GRÁFICO',
                'descripcion' => 'ESPECIALISTA EN CREACIÓN DE MATERIAL VISUAL, MANEJO DE SOFTWARE DE DISEÑO, ILUSTRACIÓN Y PRODUCCIÓN GRÁFICA.'
            ],
            [
                'nombre' => 'TÉCNICO EN SONIDO',
                'descripcion' => 'PROFESIONAL EN INSTALACIÓN Y OPERACIÓN DE EQUIPOS DE SONIDO, MEZCLA, GRABACIÓN Y PRODUCCIÓN AUDIOVISUAL.'
            ],
            [
                'nombre' => 'TÉCNICO EN MECATRÓNICA',
                'descripcion' => 'ESPECIALISTA EN SISTEMAS AUTOMATIZADOS, ROBÓTICA, CONTROL INDUSTRIAL Y INTEGRACIÓN DE TECNOLOGÍAS.'
            ],
            [
                'nombre' => 'TÉCNICO EN MINERÍA',
                'descripcion' => 'PROFESIONAL EN OPERACIONES MINERAS, SEGURIDAD EN MINERÍA, PROCESOS DE EXTRACCIÓN Y BENEFICIO DE MINERALES.'
            ]
        ];

        foreach ($profesionesTecnicas as $profesion) {
            ProfesionTecnica::create($profesion);
        }

        $this->command->info('✅ 20 PROFESIONES TÉCNICAS CREADAS EXITOSAMENTE!');
        $this->command->info('🔧 PROFESIONES INCLUIDAS: INFORMÁTICA, ELECTRICIDAD, MECÁNICA, CONSTRUCCIÓN, ENFERMERÍA, CONTABILIDAD, GASTRONOMÍA Y OTRAS ESPECIALIDADES TÉCNICAS');
    }
}