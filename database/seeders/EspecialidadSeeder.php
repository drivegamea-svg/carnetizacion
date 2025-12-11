<?php
// database/seeders/EspecialidadSeeder.php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Seeder;

class EspecialidadSeeder extends Seeder
{
    public function run()
    {
        $especialidades = [
            [
                'nombre' => 'REDES Y CONECTIVIDAD',
                'descripcion' => 'ESPECIALIZACIÓN EN INSTALACIÓN, CONFIGURACIÓN Y MANTENIMIENTO DE REDES LAN, WAN Y CONECTIVIDAD DE DATOS.'
            ],
            [
                'nombre' => 'SOPORTE TÉCNICO',
                'descripcion' => 'ESPECIALISTA EN ATENCIÓN AL USUARIO, RESOLUCIÓN DE INCIDENTES Y ASISTENCIA TÉCNICA EN SISTEMAS INFORMÁTICOS.'
            ],
            [
                'nombre' => 'INSTALACIONES ELÉCTRICAS RESIDENCIALES',
                'descripcion' => 'ESPECIALIZACIÓN EN DISEÑO E INSTALACIÓN DE SISTEMAS ELÉCTRICOS PARA VIVIENDAS Y EDIFICACIONES RESIDENCIALES.'
            ],
            [
                'nombre' => 'MANTENIMIENTO INDUSTRIAL',
                'descripcion' => 'ESPECIALISTA EN MANTENIMIENTO PREVENTIVO Y CORRECTIVO DE MAQUINARIA Y EQUIPOS INDUSTRIALES.'
            ],
            [
                'nombre' => 'MECÁNICA DE MOTORES GASOLINEROS',
                'descripcion' => 'ESPECIALIZACIÓN EN DIAGNÓSTICO Y REPARACIÓN DE MOTORES DE COMBUSTIÓN INTERNA A GASOLINA.'
            ],
            [
                'nombre' => 'ENFERMERÍA GENERAL',
                'descripcion' => 'ESPECIALISTA EN CUIDADOS INTEGRALES DE ENFERMERÍA, PROCEDIMIENTOS CLÍNICOS Y ATENCIÓN AL PACIENTE.'
            ],
            [
                'nombre' => 'CONTABILIDAD TRIBUTARIA',
                'descripcion' => 'ESPECIALIZACIÓN EN REGÍMENES TRIBUTARIOS, DECLARACIONES IMPOSITIVAS Y OBLIGACIONES FISCALES.'
            ],
            [
                'nombre' => 'COCINA INTERNACIONAL',
                'descripcion' => 'ESPECIALISTA EN TÉCNICAS CULINARIAS DE DIFERENTES PAÍSES Y CULTURAS GASTRONÓMICAS.'
            ],
            [
                'nombre' => 'SOLDADURA INDUSTRIAL',
                'descripcion' => 'ESPECIALIZACIÓN EN TÉCNICAS DE SOLDADURA MIG, TIG, ARCO Y PROCESOS DE UNIÓN DE MATERIALES.'
            ],
            [
                'nombre' => 'ELECTRÓNICA DIGITAL',
                'descripcion' => 'ESPECIALISTA EN DISEÑO Y REPARACIÓN DE CIRCUITOS ELECTRÓNICOS DIGITALES Y SISTEMAS EMBEBIDOS.'
            ],
            [
                'nombre' => 'ATENCIÓN AL CLIENTE',
                'descripcion' => 'ESPECIALIZACIÓN EN SERVICIO AL CLIENTE, COMUNICACIÓN EFECTIVA Y RESOLUCIÓN DE CONFLICTOS.'
            ],
            [
                'nombre' => 'SEGURIDAD Y SALUD OCUPACIONAL',
                'descripcion' => 'ESPECIALISTA EN GESTIÓN DE RIESGOS LABORALES, INSPECCIONES DE SEGURIDAD Y NORMATIVAS OSHA.'
            ],
            [
                'nombre' => 'RECEPCIÓN HOTELERA',
                'descripcion' => 'ESPECIALIZACIÓN EN SERVICIOS DE RECEPCIÓN, RESERVAS Y ATENCIÓN AL HUÉSPED EN ESTABLECIMIENTOS HOTELEROS.'
            ],
            [
                'nombre' => 'AGRICULTURA ORGÁNICA',
                'descripcion' => 'ESPECIALISTA EN TÉCNICAS DE CULTIVO ORGÁNICO, MANEJO ECOLÓGICO Y CERTIFICACIONES ORGÁNICAS.'
            ],
            [
                'nombre' => 'MECÁNICA DE EQUIPOS PESADOS',
                'descripcion' => 'ESPECIALIZACIÓN EN MANTENIMIENTO Y REPARACIÓN DE MAQUINARIA PESADA DE CONSTRUCCIÓN Y MINERÍA.'
            ],
            [
                'nombre' => 'CLIMATIZACIÓN COMERCIAL',
                'descripcion' => 'ESPECIALISTA EN INSTALACIÓN Y MANTENIMIENTO DE SISTEMAS DE AIRE ACONDICIONADO PARA LOCALES COMERCIALES.'
            ],
            [
                'nombre' => 'ANÁLISIS CLÍNICOS',
                'descripcion' => 'ESPECIALIZACIÓN EN PROCESAMIENTO DE MUESTRAS BIOLÓGICAS Y TÉCNICAS DE LABORATORIO CLÍNICO.'
            ],
            [
                'nombre' => 'DISEÑO WEB',
                'descripcion' => 'ESPECIALISTA EN CREACIÓN DE SITIOS WEB, DISEÑO RESPONSIVE Y EXPERIENCIA DE USUARIO (UX/UI).'
            ],
            [
                'nombre' => 'PRODUCCIÓN AUDIOVISUAL',
                'descripcion' => 'ESPECIALIZACIÓN EN GRABACIÓN, EDICIÓN Y PRODUCCIÓN DE CONTENIDO AUDIOVISUAL Y MULTIMEDIA.'
            ],
            [
                'nombre' => 'AUTOMATIZACIÓN INDUSTRIAL',
                'descripcion' => 'ESPECIALISTA EN SISTEMAS DE CONTROL AUTOMÁTICO, PLC, ROBÓTICA Y PROCESOS INDUSTRIALES AUTOMATIZADOS.'
            ]
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::create($especialidad);
        }

        $this->command->info('✅ 20 ESPECIALIDADES CREADAS EXITOSAMENTE!');
        $this->command->info('🎯 ESPECIALIDADES INCLUIDAS: REDES, SOPORTE TÉCNICO, INSTALACIONES ELÉCTRICAS, MANTENIMIENTO INDUSTRIAL Y OTRAS ESPECIALIZACIONES');
    }
}