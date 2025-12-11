<?php
// database/seeders/CiudadSeeder.php

namespace Database\Seeders;

use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    public function run()
    {
        $ciudades = [
            // Departamento de LA PAZ
            ['nombre' => 'LA PAZ', 'departamento' => 'LA PAZ'],
            ['nombre' => 'EL ALTO', 'departamento' => 'LA PAZ'],
            ['nombre' => 'VIACHA', 'departamento' => 'LA PAZ'],
            ['nombre' => 'ACHOCALLA', 'departamento' => 'LA PAZ'],
            ['nombre' => 'PATACAMAYA', 'departamento' => 'LA PAZ'],
            ['nombre' => 'COROICO', 'departamento' => 'LA PAZ'],
            ['nombre' => 'CARANAVI', 'departamento' => 'LA PAZ'],
            ['nombre' => 'SORATA', 'departamento' => 'LA PAZ'],
            ['nombre' => 'GUANAY', 'departamento' => 'LA PAZ'],
            ['nombre' => 'CHULUMANI', 'departamento' => 'LA PAZ'],

            // Departamento de SANTA CRUZ
            ['nombre' => 'SANTA CRUZ DE LA SIERRA', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'MONTERO', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'WARNES', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'LA GUARDIA', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'COTOCA', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'EL TORNO', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'SAN IGNACIO DE VELASCO', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'SAN JOSÉ DE CHIQUITOS', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'ROBORÉ', 'departamento' => 'SANTA CRUZ'],
            ['nombre' => 'PUERTO SUÁREZ', 'departamento' => 'SANTA CRUZ'],

            // Departamento de COCHABAMBA
            ['nombre' => 'COCHABAMBA', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'QUILLACOLLO', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'SACABA', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'TARIJA', 'departamento' => 'TARIJA'],
            ['nombre' => 'VILLA TUNARI', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'PUNATA', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'ARANI', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'TARATA', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'CLIZA', 'departamento' => 'COCHABAMBA'],
            ['nombre' => 'AIQUILE', 'departamento' => 'COCHABAMBA'],

            // Departamento de ORURO
            ['nombre' => 'ORURO', 'departamento' => 'ORURO'],
            ['nombre' => 'CHALLAPATA', 'departamento' => 'ORURO'],
            ['nombre' => 'HUANUNI', 'departamento' => 'ORURO'],
            ['nombre' => 'SANTIAGO DE HUARI', 'departamento' => 'ORURO'],
            ['nombre' => 'SABAYA', 'departamento' => 'ORURO'],

            // Departamento de POTOSÍ
            ['nombre' => 'POTOSÍ', 'departamento' => 'POTOSÍ'],
            ['nombre' => 'VILLAZÓN', 'departamento' => 'POTOSÍ'],
            ['nombre' => 'TUPIZA', 'departamento' => 'POTOSÍ'],
            ['nombre' => 'LLALLAGUA', 'departamento' => 'POTOSÍ'],
            ['nombre' => 'UYUNI', 'departamento' => 'POTOSÍ'],

            // Departamento de TARIJA
            ['nombre' => 'TARIJA', 'departamento' => 'TARIJA'],
            ['nombre' => 'BERMEJO', 'departamento' => 'TARIJA'],
            ['nombre' => 'VILLAMONTES', 'departamento' => 'TARIJA'],
            ['nombre' => 'YACUIBA', 'departamento' => 'TARIJA'],
            ['nombre' => 'ENTRE RÍOS', 'departamento' => 'TARIJA'],

            // Departamento de SUCRE
            ['nombre' => 'SUCRE', 'departamento' => 'CHUQUISACA'],
            ['nombre' => 'MONTEAGUDO', 'departamento' => 'CHUQUISACA'],
            ['nombre' => 'CAMARGO', 'departamento' => 'CHUQUISACA'],
            ['nombre' => 'VILLA SERRANO', 'departamento' => 'CHUQUISACA'],
            ['nombre' => 'PADILLA', 'departamento' => 'CHUQUISACA'],

            // Departamento de PANDO
            ['nombre' => 'COBIJA', 'departamento' => 'PANDO'],
            ['nombre' => 'PORVENIR', 'departamento' => 'PANDO'],
            ['nombre' => 'PUERTO RICO', 'departamento' => 'PANDO'],
            ['nombre' => 'FILADELFIA', 'departamento' => 'PANDO'],

            // Departamento de BENI
            ['nombre' => 'TRINIDAD', 'departamento' => 'BENI'],
            ['nombre' => 'RIBERALTA', 'departamento' => 'BENI'],
            ['nombre' => 'GUAYARAMERÍN', 'departamento' => 'BENI'],
            ['nombre' => 'SAN BORJA', 'departamento' => 'BENI'],
            ['nombre' => 'SANTA ANA DEL YACUMA', 'departamento' => 'BENI'],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }

        $this->command->info('✅ 50 CIUDADES BOLIVIANAS CREADAS EXITOSAMENTE!');
        $this->command->info('🏙️ CIUDADES INCLUIDAS DE TODOS LOS DEPARTAMENTOS: LA PAZ, SANTA CRUZ, COCHABAMBA, ORURO, POTOSÍ, TARIJA, CHUQUISACA, PANDO Y BENI');
    }
}