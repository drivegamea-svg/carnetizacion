<?php
// database/seeders/AfiliadoFakerSeeder.php

namespace Database\Seeders;

use App\Models\Afiliado;
use App\Models\Ciudad;
use App\Models\ProfesionTecnica;
use App\Models\Especialidad;
use App\Models\OrganizacionSocial;
use App\Models\SectorEconomico;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AfiliadoFakerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES'); // Español

        // Obtener IDs reales de las tablas relacionadas
        $ciudadesIds = Ciudad::pluck('id')->toArray();
        $profesionesIds = ProfesionTecnica::pluck('id')->toArray();
        $especialidadesIds = Especialidad::pluck('id')->toArray();
        $organizacionesIds = OrganizacionSocial::pluck('id')->toArray();
        $sectoresIds = SectorEconomico::pluck('id')->toArray();

        // Verificar que existen datos en las tablas relacionadas
        if (empty($ciudadesIds) || empty($profesionesIds) || empty($especialidadesIds) || empty($sectoresIds)) {
            $this->command->error('❌ Primero debe ejecutar los seeders de las tablas maestras (ciudades, profesiones, especialidades, sectores económicos)');
            return;
        }

        $expediciones = ['LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'];
        $direcciones = [
            'AVENIDA BALLIVIAN CALLE 5',
            'ZONA CENTRAL MERCADO CAMPERO',
            'CALLE JUNIN ESQ. SUCRE',
            'AVENIDA CIRCUNVALACION N° 125',
            'ZONA VILLA FATIMA CALLE 3',
            'PLAZA PRINCIPAL EDIFICIO COMERCIAL',
            'AVENIDA BUSCH N° 450',
            'ZONA SUR CALLE MEXICO',
            'BARRIO LOS OLIVOS CALLE 8',
            'AVENIDA AMERICAS #789'
        ];

        for ($i = 0; $i < 50; $i++) {
            $estado = $faker->randomElement(['ACTIVO', 'PENDIENTE', 'INACTIVO']);
            
            $afiliado = [
                'id' => \Illuminate\Support\Str::uuid(),
                'ci' => $faker->unique()->numberBetween(5000000, 9999999),
                'expedicion' => $faker->randomElement($expediciones),
                'nombres' => $faker->firstName() . ' ' . $faker->firstName(),
                'apellido_paterno' => $faker->lastName(),
                'apellido_materno' => $faker->lastName(),
                'fecha_nacimiento' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'genero' => $faker->randomElement(['MASCULINO', 'FEMENINO']),
                'celular' => '7' . $faker->numberBetween(1000000, 9999999),
                'direccion' => $faker->randomElement($direcciones),
                
                // Relaciones con IDs reales
                'ciudad_id' => $faker->randomElement($ciudadesIds),
                'profesion_tecnica_id' => $faker->randomElement($profesionesIds),
                'especialidad_id' => $faker->randomElement($especialidadesIds),
                'organizacion_social_id' => $faker->optional(0.5)->randomElement($organizacionesIds), // 50% de probabilidad
                'sector_economico_id' => $faker->randomElement($sectoresIds),
                
                'estado' => $estado,
                'foto_path' => null,
                'foto_data' => null,
            ];

            // Solo agregar fechas si está ACTIVO o INACTIVO
            if ($estado === 'ACTIVO') {
                $fechaAfiliacion = $faker->dateTimeBetween('-4 years', 'now');
                $afiliado['fecha_afiliacion'] = $fechaAfiliacion;
                $afiliado['fecha_vencimiento'] = Carbon::parse($fechaAfiliacion)->addYears(2);
            } elseif ($estado === 'INACTIVO') {
                $fechaAfiliacion = $faker->dateTimeBetween('-4 years', '-1 year');
                $afiliado['fecha_afiliacion'] = $fechaAfiliacion;
                $afiliado['fecha_vencimiento'] = Carbon::parse($fechaAfiliacion)->addYears(2);
            }

            // Crear afiliado
            Afiliado::create($afiliado);
        }

        $this->command->info('✅ 50 afiliados falsos creados exitosamente!');
        $this->command->info('📊 Distribución:');
        $this->command->info('   - Activos: ' . Afiliado::where('estado', 'ACTIVO')->count());
        $this->command->info('   - Pendientes: ' . Afiliado::where('estado', 'PENDIENTE')->count());
        $this->command->info('   - Inactivos: ' . Afiliado::where('estado', 'INACTIVO')->count());
    }
}